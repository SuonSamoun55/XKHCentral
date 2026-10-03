<?php

namespace App\Services;

use App\Models\ManagementSystem\Company;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

/**
 * Copies a company's setup (roles included), staff and items into a new
 * "test" company.
 *
 * Staff are copied only when a staff email tag is given, as new, separate
 * accounts pinned to the test company, with the tag added to their email
 * (admin@gmail.com + "xkh" -> adminxkh@gmail.com, + ".xkh" -> admin.xkh@gmail.com) so each
 * login reaches exactly one company. Editing or deleting a test staff account
 * never touches the live one, and the other way round.
 *
 * Customers are not copied, and neither is anything that belongs to a user
 * (orders, carts, favorites, notifications, chat): copies of those would have
 * to point at the real accounts, and some customer screens don't filter by
 * company, so real users would see test data.
 *
 * Rows are copied column-for-column with the query builder (not Eloquent),
 * so columns added later are cloned automatically. Foreign keys pointing at
 * rows that were cloned are rewritten to the new ids; ones pointing at rows
 * that weren't (global roles, the cross-company support admin, ...) are kept.
 *
 * The ORDER number series prefix gets a "T{id}" prefix, so the test
 * company's new orders never reuse a real order number (orders.order_no is
 * unique across all companies).
 */
class CompanyCloneService
{
    /** @var array<string, array<int, int>> table => [old id => new id] */
    private array $map = [];

    /** @var string[] files copied on the public disk, removed again on failure */
    private array $copiedFiles = [];

    private int $newCompanyId;

    private string $testTag;

    /**
     * @param array{name: string, staff_email_tag?: ?string} $options staff are copied only when a tag is given
     */
    public function clone(Company $source, array $options): Company
    {
        $this->map = [];
        $this->copiedFiles = [];
        $staffEmailTag = filled($options['staff_email_tag'] ?? null) ? $options['staff_email_tag'] : null;

        try {
            return DB::transaction(function () use ($source, $options, $staffEmailTag) {
                $this->cloneCompany($source, $options['name'], $staffEmailTag);
                $this->cloneSetup($source->id);
                if ($staffEmailTag !== null) {
                    $this->cloneStaff($source, $staffEmailTag);
                }
                $this->cloneItems($source->id);

                return Company::findOrFail($this->newCompanyId);
            });
        } catch (Throwable $e) {
            foreach ($this->copiedFiles as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $e;
        }
    }

    private function cloneCompany(Company $source, string $name, ?string $staffEmailTag): void
    {
        $this->copyRows('companies', DB::table('companies')->where('id', $source->id), [], function (array $row) use ($name, $source, $staffEmailTag) {
            $row['name'] = $name;
            $row['display_name'] = $name;
            $row['is_test'] = true;
            $row['cloned_from_id'] = $source->id;
            if (array_key_exists('staff_email_tag', $row)) {
                $row['staff_email_tag'] = $staffEmailTag;
            }
            // The clone is a new company: it's created now, not when the source was.
            $row['created_at'] = $row['updated_at'] = now();

            foreach (['logo', 'favicon', 'company_image'] as $fileColumn) {
                if (array_key_exists($fileColumn, $row)) {
                    $row[$fileColumn] = $this->copyFile($row[$fileColumn]);
                }
            }

            return $row;
        });

        $this->newCompanyId = $this->map['companies'][$source->id];
        $this->testTag = 'T' . $this->newCompanyId;
    }

    private function cloneSetup(int $sourceId): void
    {
        // Copied as-is, status included, so the clone behaves like the source.
        // When on, confirming a test order sends it to the same Business
        // Central company; it can be switched off on the clone's API Setup page.
        $this->copyRows('company_connections', $this->scoped('company_connections', $sourceId));

        $this->copyRows('roles', $this->scoped('roles', $sourceId));
        $this->copyRows(
            'role_permissions',
            DB::table('role_permissions')->whereIn('role_id', $this->idsOf('roles', $sourceId)),
            ['role_id' => 'roles']
        );

        $this->copyRows('number_series', $this->scoped('number_series', $sourceId), [], function (array $row) {
            if ($row['code'] === 'ORDER') {
                // Cloning a test company: swap its "T{id}" tag instead of stacking a second one.
                $basePrefix = preg_replace('/^(T\d+)+/', '', $row['prefix']);
                $row['prefix'] = Str::limit($this->testTag . $basePrefix, 10, '');
            }
            if ($row['code'] !== 'ITEM') {
                $row['last_no'] = null;
                $row['last_used_at'] = null;
            }

            return $row;
        });

        $this->copyRows('tax_groups', $this->scoped('tax_groups', $sourceId));
        $this->copyRows('vat_posting_setups', $this->scoped('vat_posting_setups', $sourceId));
        $this->copyRows('store_settings', $this->scoped('store_settings', $sourceId));
        $this->copyRows('report_settings', $this->scoped('report_settings', $sourceId), [], function (array $row) {
            $row['logo'] = $this->copyFile($row['logo']);

            return $row;
        });
    }

    /**
     * Copy the company's staff as new accounts of the test company. Each gets
     * a tagged email, its own staff code and its own copy of its photo, and
     * keeps its password so it signs in the same way as the live account.
     */
    private function cloneStaff(Company $source, string $tag): void
    {
        $staff = $this->scoped('users', $source->id)->where('bc_customer_no', 'like', 'STAFF-%');

        // When cloning a test company, swap its tag instead of stacking a second one.
        $sourceTag = $source->is_test ? $source->staff_email_tag : null;

        $emails = [];
        foreach ((clone $staff)->whereNotNull('email')->pluck('email') as $email) {
            $emails[$email] = $this->taggedEmail($email, $tag, $sourceTag);
        }

        $taken = DB::table('users')->whereIn('email', array_values($emails))->pluck('email');
        if ($taken->isNotEmpty()) {
            throw new RuntimeException(
                'these staff emails are already in use: ' . $taken->implode(', ') . '. Use a different email tag.'
            );
        }

        $this->copyRows('users', $staff, ['role_id' => 'roles'], function (array $row) use ($emails) {
            if ($row['email'] !== null) {
                $row['email'] = $emails[$row['email']];
            }
            $row['bc_customer_no'] = 'STAFF-' . strtoupper(Str::random(10));
            $row['last_company_id'] = null;
            $row['remember_token'] = null;
            $row['last_seen_at'] = null;
            $row['created_at'] = $row['updated_at'] = now();

            foreach (['profile_image', 'avatar'] as $fileColumn) {
                if (array_key_exists($fileColumn, $row)) {
                    $row[$fileColumn] = $this->copyFile($row[$fileColumn]);
                }
            }

            return $row;
        });
    }

    /** The tag is added exactly as typed: admin@gmail.com + ".xkh" -> admin.xkh@gmail.com, + "xkh" -> adminxkh@gmail.com. A trailing $sourceTag is swapped out first. */
    private function taggedEmail(string $email, string $tag, ?string $sourceTag): string
    {
        $at = strrpos($email, '@');
        $local = $at === false ? $email : substr($email, 0, $at);
        $domain = $at === false ? '' : substr($email, $at);

        if (filled($sourceTag)) {
            $local = preg_replace('/' . preg_quote($sourceTag, '/') . '$/i', '', $local);
        }

        return $local . $tag . $domain;
    }

    private function cloneItems(int $sourceId): void
    {
        $sourceItems = $this->idsOf('items', $sourceId);

        $this->copyRows('items', $this->scoped('items', $sourceId), ['number_series_id' => 'number_series']);
        $this->copyRows('item_variants', DB::table('item_variants')->whereIn('item_id', $sourceItems), ['item_id' => 'items']);
        $this->copyRows('item_setup_statuses', DB::table('item_setup_statuses')->whereIn('item_id', $sourceItems), ['item_id' => 'items']);
        $this->copyRows('item_location_inventories', $this->scoped('item_location_inventories', $sourceId), ['item_id' => 'items']);
    }

    /**
     * Copy every row matched by $query into the same table, remapping
     * company_id / last_company_id plus the given $foreignKeys, and record
     * old id => new id for later tables.
     *
     * @param array<string, string> $foreignKeys column => table whose id map to use
     */
    private function copyRows(string $table, Builder $query, array $foreignKeys = [], ?callable $transform = null): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        $foreignKeys += ['company_id' => 'companies', 'last_company_id' => 'companies'];
        $this->map[$table] ??= [];

        foreach ($query->lazyById(200) as $record) {
            $row = (array) $record;
            $oldId = $row['id'];
            unset($row['id']);

            foreach ($foreignKeys as $column => $mapTable) {
                if (isset($row[$column])) {
                    $row[$column] = $this->map[$mapTable][$row[$column]] ?? $row[$column];
                }
            }

            if ($transform) {
                $row = $transform($row);
            }

            $this->map[$table][$oldId] = DB::table($table)->insertGetId($row);
        }
    }

    private function scoped(string $table, int $companyId): Builder
    {
        return DB::table($table)->where('company_id', $companyId);
    }

    private function idsOf(string $table, int $companyId): Builder
    {
        return $this->scoped($table, $companyId)->select('id');
    }

    /**
     * Give the clone its own copy of an uploaded file, since updating or
     * deleting a logo deletes the old file from disk and would
     * otherwise break the original company.
     */
    private function copyFile(?string $path): ?string
    {
        $disk = Storage::disk('public');

        if (blank($path) || !$disk->exists($path)) {
            return $path;
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $newPath = trim(dirname($path), './') . '/' . Str::random(40) . ($extension ? ".{$extension}" : '');
        $disk->copy($path, $newPath);
        $this->copiedFiles[] = $newPath;

        return $newPath;
    }
}
