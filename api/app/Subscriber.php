<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Model
{
    use SoftDeletes;

    protected $table = 'subscribers';

    public $timestamps = true;

    protected $fillable = [
        'name', 'email',
        'satId', 'noradId',
        'location', 'latitude', 'longitude', 'altitude',
    ];

}
