<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
use App\Mail\SendMail;
use App\Models\Transaksi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

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
        $data["title"] = "From PT.Disty Teknologi Indonesia";
        $data["body"] = "This is Demo";

        $today = Carbon::today();
        $nextThreeDays = $today->addDays(3);
        $nextSevenDays = $today->addDays(3);

        $transaksi = Transaksi::whereDate('jatuh_tempo', $today)
            ->orWhereDate('jatuh_tempo', '<=', $nextThreeDays)
            ->orWhereDate('jatuh_tempo', '<=', $nextSevenDays)
            ->where('status_tagihan', 'Belum Bayar')
            ->with('pelanggan')
            ->with('user')
            ->get();


            // $transaksi = Transaksi::where('jatuh_tempo', Carbon::today())->where('status_tagihan', 'Belum Bayar')->with('pelanggan')->get();
        try {
            foreach ($transaksi as $trx) {
                $data["email"] = $trx->user->email;
                $data["firstname"] = $trx->user->firstname;
                $data["lastname"] = $trx->user->lastname;
                $data["address"] = $trx->user->address;
                $data["no_tagihan"] = $trx->no_tagihan;
                $data["produk"] = $trx->produk;
                $data["jumlah_tagihan"] = $trx->jumlah_tagihan;
                $data["jatuh_tempo"] = $trx->jatuh_tempo;

                $pdf = PDF::loadView('emails.invoice', $data);
                $pdf->setPaper('A4', 'landscape');

                $mail = Mail::send('emails.mail', $data, function($message) use ($data, $pdf) {
                    $message->to($data["email"], $data["firstname"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), "Invoice.pdf");
                });
                Log::info(json_encode($mail));
                Log::info(json_encode($transaksi));
            }
        } catch (Exception $e) {
            //Give response message error if failed to send email
            Log::info($e);
            response()->json([$e->getMessage()]);
        }

        // foreach ($transaksi as $t) {
        //     $data = array(
        //         'subject'   => $t->subject,
        //         'no_tagihan'=> $t->no_tagihan,
        //         'email'     => $t->pelanggan->email,
        //         'name'      => $t->pelanggan->name,
        //         //Send Request is send_feedback
        //         'request'   => 'send'
        //     );

        // }
    }
}
