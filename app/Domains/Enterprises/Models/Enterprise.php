<?php

namespace App\Domains\Enterprises\Models;

use App\Domains\Stocks\Models\Stock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enterprise extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'website',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
