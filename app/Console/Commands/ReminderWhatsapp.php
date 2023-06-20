<?php

namespace App\Console\Commands;

use DateTime;
use Exception;
use Carbon\Carbon;
use App\Models\Status;
use App\Models\Transaksi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReminderWhatsapp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:whatsapp';

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

        try {
            echo $time . "\n";
            if($time == $status->time){
                echo 'Gas';
                foreach ($transaksi as $trx) {
                    $curl = curl_init();

                    $data["email"] = $trx->user->email;
                    $data["firstname"] = $trx->user->firstname;
                    $data["lastname"] = $trx->user->lastname;
                    $data["address"] = $trx->user->address;
                    $data["no_telp"] = $trx->pelanggan->no_telp;
                    $data["produk"] = $trx->produk;
                    $data["jumlah_tagihan"] = $trx->jumlah_tagihan;
                    $data["jatuh_tempo"] = $trx->jatuh_tempo;


                    $firstname = $data["firstname"];
                    $lastname = $data["lastname"];
                    $jatuh_tempo = $data["jatuh_tempo"];
                    $pesan = "Halo {$firstname} {$lastname},\n\nIni adalah pengingat untuk pembayaran tagihan Anda. Jangan lupa untuk segera melakukan pembayaran sebelum jatuh tempo ya.\n\nJatuh Tempo: {$jatuh_tempo} \n\nTerima kasih.";


                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.fonnte.com/send',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => array(
                            'target' => $data["no_telp"],
                            'message' => $pesan,
                            'delay' => '2',
                            'countryCode' => '62', //optional
                        ),
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: YHGJYEVA4wjNZzFpHCkm' //change TOKEN to your actual token
                        ),
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                }
                echo $transaksi;
                Log::info(json_encode($response));
                echo $response;
            } else {
                echo "Tidak ada pengingat Whatsapp saat ini\n";
            }
        } catch (Exception $e) {
            //Give response message error if failed to send email
            Log::info($e);
            response()->json([$e->getMessage()]);
        }
    }
}
