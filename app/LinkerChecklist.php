<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkerChecklist extends Model
{
    protected $table = 'linker_checklist';
    protected $fillable = ['checklist_id','task_id'];
}
