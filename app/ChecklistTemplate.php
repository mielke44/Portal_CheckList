<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChecklistTemplate extends Model
{
    protected $table = 'checklist_template';
    protected $fillable = ['name'];

    public function tasks() {
        return $this->belongsToMany(Task::class, 'linker_checklist');
    }

    public function profiles() {
        return $this->belongsToMany(Profile::class, 'profile_linker');
    }
}
