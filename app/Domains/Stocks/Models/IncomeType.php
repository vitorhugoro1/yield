<?php

namespace App\Domains\Stocks\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class IncomeType extends Model
{
    use Sushi;

    protected $rows = [
        [
            'type' => 'dividend',
            'name' => 'Dividend Yield',
        ],
        [
            'type' => 'interest',
            'name' => 'Interest on Capital',
        ],
        [
            'type' => 'subsidies',
            'name' => 'Subsidies',
        ],
        [
            'type' => 'subscription_right',
            'name' => 'Subscription Right',
        ],
    ];
}
