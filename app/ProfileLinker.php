<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileLinker extends Model
{
    protected $table= 'profile_linker';
    protected $fillable=['checklist_id','profile_id'];
}
