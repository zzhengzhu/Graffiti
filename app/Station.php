<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// It must use the SpatialTrait and define an array called spatialFields
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends Model
{
    use SpatialTrait;
    use SoftDeletes;
    //Table Name
    protected $table = 'stations';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;

    //specify spatial fields
    protected $spatialFields = [
        'location',
    ];
    protected $dates = ['deleted_at'];
}
