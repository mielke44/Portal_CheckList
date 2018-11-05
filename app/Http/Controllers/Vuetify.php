<?php

namespace App\Http\Controllers;

class Vuetify extends Controller
{
    public function jsonMessage($id)
    {
        $data = array("message1" => array("text" => "This message is been getting by ajax request - JQuery Ajax"));
        return json_encode($data[$id]);
    }
}
