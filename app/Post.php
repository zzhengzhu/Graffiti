<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// It must use the SpatialTrait and define an array called spatialFields
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Post extends Model
{
    use SpatialTrait;
    //Table Name
    protected $table = 'posts';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;

    //specify spatial fields
    protected $spatialFields = [
        'location',
    ];
}
