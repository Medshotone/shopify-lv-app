<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Install extends Model
{
    protected $fillable = ['store', 'nonce', 'access_token'];
    public $timestamps = false;

}
