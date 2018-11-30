<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    protected $table = 'notification';
    protected $fillable = ['text','name' ,'admin_id','employee_id','type', 'check_id','status'];

}
