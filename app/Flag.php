<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
    protected $table = 'flag';
    protected $fillable = ['type','receiver'];
}
