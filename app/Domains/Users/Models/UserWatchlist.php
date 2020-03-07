<?php

namespace App\Domains\Users\Models;

use App\Domains\Stocks\Models\Stock;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserWatchlist extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'stock_id', 'email', 'sms',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
