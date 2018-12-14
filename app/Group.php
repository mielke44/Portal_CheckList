<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table= 'group';
    protected $fillable=['name'];
    protected $casts =['lists'=>'array'];
}
