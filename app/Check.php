<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    protected $table= 'check';
    protected $fillable= ['resp','status','comment','task_id','checklist_id'];
}
