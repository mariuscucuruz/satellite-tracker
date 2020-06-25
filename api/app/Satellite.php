<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Satellite extends Model
{

    use SoftDeletes;

    protected $table = 'satellites';

    public $timestamps = true;

    protected $fillable = [
        'name', 'noradId',
    ];

}
