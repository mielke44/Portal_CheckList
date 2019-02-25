<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';
    protected $fillable = [
        'name', 'description', 'type', 'resp', 'limit'
    ];
    public function checklists() {
        return $this->belongsToMany(ChecklistTemplate::class, 'linker_checklist');
    }
}
