<?php

namespace App\Domains\Parsers\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SourceData extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'link', 'active',
    ];
}
