<?php

namespace App\Console\Commands;

use Exception;
use Carbon\Carbon;
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

        $curl = curl_init();
        try {
            foreach ($transaksi as $trx) {
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
                $pesan = "Halo {$firstname} {$lastname},\n\nTerima kasih telah melunasi tagihan Anda. Kami mengapresiasi kerjasama Anda dalam melakukan pembayaran tepat waktu.\n\nTerima kasih dan semoga harimu menyenangkan.";


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

            }
            $response = curl_exec($curl);
            Log::info(json_encode($response));
            curl_close($curl);
            echo $response;
        } catch (Exception $e) {
            //Give response message error if failed to send email
            Log::info($e);
            response()->json([$e->getMessage()]);
        }
    }
}
