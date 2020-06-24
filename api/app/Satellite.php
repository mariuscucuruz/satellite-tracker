<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Satellite extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'satellites';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
//    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'noradId',
    ];

}
