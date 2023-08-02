<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NormalMail extends Mailable
{
    use Queueable, SerializesModels;

    private $title;
    private $content;
    public function __construct($title, $content)
    {
        $this->title = $title;
        $this->content = str_replace("\n", "<br>", $content);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.text')
                    ->subject($this->title)
                    ->with([
                        'title' =>  $this->title,
                        'msg'   =>  $this->content
                    ]);;
    }
}
