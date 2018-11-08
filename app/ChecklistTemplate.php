<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChecklistTemplate extends Model
{
    protected $table = 'checklist_template';
    protected $fillable = [
        'name','type'
    ];
}
