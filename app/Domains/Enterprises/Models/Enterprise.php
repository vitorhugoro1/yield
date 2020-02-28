<?php

namespace App\Domains\Enterprises\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enterprise extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'website',
    ];
}
