<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    //Table Name
    protected $table = 'updates';
    //Primary Key
    public $primaryKey = 'id';
    //Timestamps
    public $timestamps = true;
}
