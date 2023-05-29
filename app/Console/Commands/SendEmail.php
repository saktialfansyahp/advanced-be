<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use App\Mail\SendMail;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {

    	//if the number of errors is more than zero
        $transaksi = Transaksi::with('pelanggan')->get();
        foreach ($transaksi as $t) {
            $name = $t->user->firstname .  ' ' . $t->user->lastname;
            $data = array(
                'subject'   => $t->subject,
                'no_tagihan'=> $t->no_tagihan,
                'email'     => $t->user->email,
                'name'      => $name,
                //Send Request is send_feedback
                'request'   => 'send'
            );
        }
            //Send Email with model of email SendEmail and with variable data
        $mail = Mail::send(new SendMail($data));

        //Check if sending email failure
        if ($mail) {
            //Give response message success if success to send email
            $status = 200;
        } else {
            //Give response message failed if failed to send email
            $status = 400;
        }
        return $status;
    }
}
