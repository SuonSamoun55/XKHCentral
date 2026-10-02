<?php

namespace App\Services;

use App\Models\ManagementSystem\Company;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Copies a company's setup (roles included) and items into a new "test"
 * company.
 *
 * No user accounts are copied — neither customers nor staff — and so neither
 * is anything that belongs to one (orders, carts, favorites, notifications,
 * chat): copies of those would have to point at the real accounts, and some
 * customer screens don't filter by company, so real users would see test data.
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
     * @param array{name: string} $options
     */
    public function clone(Company $source, array $options): Company
    {
        $this->map = [];
        $this->copiedFiles = [];

        try {
            return DB::transaction(function () use ($source, $options) {
                $this->cloneCompany($source, $options['name']);
                $this->cloneSetup($source->id);
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

    private function cloneCompany(Company $source, string $name): void
    {
        $this->copyRows('companies', DB::table('companies')->where('id', $source->id), [], function (array $row) use ($name, $source) {
            $row['name'] = $name;
            $row['display_name'] = $name;
            $row['is_test'] = true;
            $row['cloned_from_id'] = $source->id;
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
