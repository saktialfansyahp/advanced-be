<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    // public function envelope()
    // {
    //     return new Envelope(
    //         subject: 'Send Mail',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  *
    //  * @return \Illuminate\Mail\Mailables\Content
    //  */
    // public function content()
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array
    //  */
    // public function attachments()
    // {
    //     return [];
    // }
    // public function build()
    // {
    //     $pdf = PDF::loadView('emails.index', $this->data);

    //     return $this->from('pegaxus81@gmail.com', 'sakti alfansyah putra')
    //         ->to($this->data['email'])
    //         ->subject($this->data['subject'])
    //         ->view('emails.index')
    //         ->with('data', $this->data)
    //         ->attachData($pdf->output(), 'invoice.pdf', [
    //             'mime' => 'application/pdf',
    //         ]);
    // }
    public function build()
    {
        return $this->from('pegaxus81@gmail.com', 'sakti alfansyah putra')
            ->to($this->data['email'])
            ->subject($this->data['subject'])
            ->view('emails.index')
            ->with('data', $this->data);
    }
}
