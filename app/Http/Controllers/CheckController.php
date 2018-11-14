<?php

namespace App\Http\Controllers;

use App\Check;
use Illuminate\Http\Request;

class CheckController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Check = Check::where("checklist_id",$request['id'],"task_id",$request['id'])->get();
            $Check->resp = $request["resp"];
            $Check->status = false;
            $Check->comment = "";
            $Check->save();
    }
}
