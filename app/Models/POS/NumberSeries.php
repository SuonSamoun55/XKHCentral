<?php

namespace App\Models\POS;

use App\Models\ManagementSystem\Company;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class NumberSeries extends Model
{
    protected $table = 'number_series';

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'prefix',
        'padding',
        'start_no',
        'end_no',
        'last_no',
        'last_used_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'padding' => 'integer',
        'start_no' => 'integer',
        'end_no' => 'integer',
        'last_no' => 'integer',
        'last_used_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function formatNumber(int $number): string
    {
        return $this->prefix . str_pad((string) $number, $this->padding, '0', STR_PAD_LEFT);
    }

    public function getNextRawNumber(): int
    {
        return ($this->last_no ?? ($this->start_no - 1)) + 1;
    }

    public function getStartFormattedAttribute(): string
    {
        return $this->formatNumber($this->start_no);
    }

    public function getEndFormattedAttribute(): string
    {
        return $this->formatNumber($this->end_no);
    }

    public function getNextFormattedAttribute(): string
    {
        return $this->formatNumber(min($this->getNextRawNumber(), $this->end_no));
    }

    public function getIsExhaustedAttribute(): bool
    {
        return $this->getNextRawNumber() > $this->end_no;
    }

    /**
     * Issue and persist the next number for this series. Caller must run
     * this inside a DB transaction with the row locked (see issue()) so
     * concurrent requests never hand out the same number twice.
     */
    public function nextNumber(): string
    {
        $next = $this->getNextRawNumber();

        if ($next > $this->end_no) {
            throw new RuntimeException(
                "Number series [{$this->code}] has reached its end number ({$this->end_formatted}). "
                . 'Extend it in Number Series setup before generating more.'
            );
        }

        $this->last_no = $next;
        $this->last_used_at = now();
        $this->save();

        return $this->formatNumber($next);
    }

    /**
     * Look up an active series by code for the given company, lock it for
     * update, and issue the next number. Call this from inside an existing
     * DB::transaction() so the lock actually holds — e.g.:
     *
     *   DB::transaction(fn () => NumberSeries::issue($companyId, 'ORDER'));
     */
    public static function issue(int $companyId, string $code): string
    {
        $series = static::where('company_id', $companyId)
            ->where('code', $code)
            ->where('is_active', true)
            ->lockForUpdate()
            ->first();

        if (!$series) {
            throw new RuntimeException("Number series [{$code}] is not set up for this company yet.");
        }

        return $series->nextNumber();
    }
}
