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
    public $data;

    public function __construct($demo,$data)
    {
        $this->demo = $demo;
        $this->data = $data;
    }

    public function build()
    {
        return $this->from('Checklist.no-reply@webexchange.t-systems.com.br', 'Portal CheckList')
                    ->to($data)
                    ->subject('Nova atualização no portal!')
                    ->view('mail')->with(['demo'=> $this->demo]);

    }
}