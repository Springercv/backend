<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookedTourInforMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $account;
    public $data;
    public $attach;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($account, $data, $attach)
    {
        $this->account = $account;
        $this->data = $data;
        $this->attach = $attach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.tour_infor');
    }
}
