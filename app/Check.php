<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Task;

class Check extends Model
{
    protected $table= 'check';
    protected $fillable= ['resp','status','task_id','checklist_id','limit'];

    public function getTemplate(){
        return Task::find($this->task_id);
    }
}
