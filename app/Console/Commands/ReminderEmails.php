<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use App\Mail\SendMail;
use App\Models\Transaksi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:email';

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
    public function handle()
    {
        $transaksi = Transaksi::where('jatuh_tempo', Carbon::today())->where('status_tagihan', 'Belum Bayar')->with('pelanggan')->get();

        foreach ($transaksi as $t) {
            $data = array(
                'subject'   => $t->subject,
                'no_tagihan'=> $t->no_tagihan,
                'email'     => $t->pelanggan->email,
                'name'      => $t->pelanggan->name,
                //Send Request is send_feedback
                'request'   => 'send'
            );

            try {
                //Send Email with model of email SendEmail and with variable data
                Mail::send(new SendMail($data));

                //Check if sending email failure
                if (!Mail::failures()) {
                    //Give response message success if success to send email
                    response()->json(['Message' => 'Success']);
                } else {
                    //Give response message failed if failed to send email
                    response()->json(['Message' => 'Failed']);
                }

            } catch (Exception $e) {
                //Give response message error if failed to send email
                response()->json([$e->getMessage()]);
            }
        }
    }
}
