<?php

namespace App\Console\Commands;

use DateTime;
use Exception;
use Carbon\Carbon;
use App\Mail\SendMail;
use App\Models\Status;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
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
        $currentTime = new DateTime();
        $time = $currentTime->format("H:i:00");

        $status = Status::where('name', 'reminder')->first();

        $daystoadd = $status->daystoadd1;
        $daystoadd2 = $status->daystoadd2;

        $currentDate = new DateTime();
        $date = $currentDate->format("Y-m-d");

        $day1 = new DateTime();
        $day1->modify('+' . $daystoadd . 'days');
        $day1formatted = $day1->format("Y-m-d");

        $day2 = new DateTime();
        $day2->modify('+' . $daystoadd2 . 'days');
        $day2formatted = $day2->format("Y-m-d");

        $transaksi = Transaksi::where('status_tagihan', 'Belum Bayar')
            ->where('jatuh_tempo', '<=', $date)
            ->orwhere('jatuh_tempo', $day1formatted)
            ->orwhere('jatuh_tempo', $day2formatted)
            ->with('pelanggan')
            ->with('user')
            ->with('transaksi_produk.produk')
            ->get();


        $data = $transaksi->map(function($item) {
            return [
                "email" => $item->user->email,
                "firstname" => $item->user->firstname,
                "lastname" => $item->user->lastname,
                "address" => $item->user->address,
                "no_tagihan" => $item->no_tagihan,
                "jumlah_tagihan" => $item->jumlah_tagihan,
                "jatuh_tempo" => $item->jatuh_tempo,
                "produk" => $item->transaksi_produk->toArray(),
            ];
        })->toArray();

        try {
            if($time == $status->time){
                echo $date;
                $status = Status::where('name', 'reminder')->first();
                Log::info($status);
                $statuses = []; // Array to store email sending statuses

                foreach ($data as $datum) {
                    Log::info($datum);
                    $pdf = PDF::loadView('emails.invoices', ['data' => $datum]);
                    $pdf->setPaper('A4', 'landscape');


                    $mail = Mail::send('emails.mail', $datum, function($message) use ($datum, $pdf) {
                        $message->to($datum["email"], $datum["firstname"])
                            ->subject('Reminder')
                            ->attachData($pdf->output(), "Invoice.pdf");
                    });

                    if ($mail) {
                        $statuses[] = 200; // Save email sending status as 200 (Success)
                    } else {
                        $statuses[] = 400; // Save email sending status as 400 (Failed)
                    }
                }
                return $statuses;

                Log::info($statuses);
            } else {
                echo "Tidak ada pengingat Email saat ini\n";
            }
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
