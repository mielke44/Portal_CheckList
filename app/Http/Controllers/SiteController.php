<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Site;

class SiteController extends Controller
{
    public function list(){
        $sites = Site::all();
        foreach($sites as $s){
            $s->complete_name = $s->name.' - '.$s->city.' - '.$s->state;
        }
        return json_encode($sites);
    }
}
