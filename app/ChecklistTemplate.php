<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChecklistTemplate extends Model
{
    protected $table = 'checklist_template';
    protected $fillable = ['name','profile_id'];

    public function tasks() {
        return $this->belongsToMany(Task::class, 'linker_checklist');
    }
}
