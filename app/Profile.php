<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profile';
    protected $fillable = ['name'];

    public function checklists() {
        return $this->belongsToMany(ChecklistTemplate::class, 'profile_linker');
    }
}
