<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $table = 'checklist';
    protected $fillable = [
        'employee_id', 'checklist_template_id'
    ];
}
