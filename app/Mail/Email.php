<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Email extends Mailable
{
    use Queueable, SerializesModels;
    
    public $demo;

    public function __construct($demo)
    {
        $this->demo = $demo;
    }

    public function build()
    {
        return $this->from(['address'=>'Checklist_no-reply@webexchange.t-systems.com.br', 'name'=>'Portal CheckList'])
                    ->subject('Nova atualização no portal!')
                    ->view('mail')->with(['demo'=> $this->demo]);

    }
}