<?php

namespace App\Models\ManagementSystem;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $table = 'exchange_rates';

    protected $fillable = [
        'company_id',
        'currency_code',
        'relational_currency_code',
        'exchange_rate_amount',
        'relational_exchange_rate_amount',
        'adjustment_exchange_rate_amount',
        'relational_adjustment_exch_rate_amt',
        'starting_date',
        'last_fetched_at',
    ];

    protected $casts = [
        'starting_date' => 'date',
        'last_fetched_at' => 'datetime',
        'exchange_rate_amount' => 'decimal:6',
        'relational_exchange_rate_amount' => 'decimal:6',
        'adjustment_exchange_rate_amount' => 'decimal:6',
        'relational_adjustment_exch_rate_amt' => 'decimal:6',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}