<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ChecklistTemplate;

class Checklist extends Model
{
    protected $table = 'checklist';
    protected $fillable = [
        'employee_id', 'checklist_template_id', 'gestor'
    ];
    public function getTemplate(){
        return ChecklistTemplate::find($this->checklist_template_id);
    }
}
