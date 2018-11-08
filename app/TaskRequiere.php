<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskRequiere extends Model
{
    protected $table = 'task_requiere';
    protected $fillable = [
        'task_id', 'task_requiere_id'
    ];
}
