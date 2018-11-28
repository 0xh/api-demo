<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AcceptFriend extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('2solid.system@gmail.com')
                    ->view('mail');
    }
}
//Mailgun Sandbox <postmaster@sandboxd4235e7d057f4f0b80f909cb369d227e.mailgun.org>
//2solid <2solid.system@gmail.com>