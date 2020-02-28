<?php

namespace App\Domains\Stocks\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockYield extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'stock_id', 'source_data_id', 'income_type', 'payed_at', 'negociated_at', 'amount',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function types()
    {
        return $this->belongsTo(IncomeType::class, 'type', 'income_type');
    }
}
