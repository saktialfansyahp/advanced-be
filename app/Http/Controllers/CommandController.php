<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CommandController extends Controller
{
    public function runCommand(Request $request)
    {
        // Mendapatkan nama command dari permintaan
        $command = 'send:email';
        $transaksi = Transaksi::where('jatuh_tempo', Carbon::now()->subDay())->get();
        try {
            if ($command === 'send:email' && $transaksi->isNotEmpty()) {
                // Menjalankan command reminder:email menggunakan Artisan::call()
                $output = Artisan::call('send:email');

                // Mengembalikan output sebagai respons
                return response()->json(['message' => 'Command executed successfully.', 'output' => $output]);
            } else {
                // Jika command tidak ditemukan, kembalikan respons dengan pesan kesalahan
                return response()->json(['message' => 'Tidak ada Invoice.'], 404);
            }
        } catch (\Exception $e) {
            // Menangani kesalahan jika command gagal dijalankan
            return response()->json(['message' => 'Failed to execute command.', 'error' => $e->getMessage()], 500);
        }
    }
    public function tes(){
        // $transaksi = Transaksi::where('jatuh_tempo', Carbon::today())->where('status_tagihan', 'Belum Bayar')->with('pelanggan')->get();
        $transaksi = Transaksi::with('user')->with('pelanggan')->get();
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
            // return $t->jatuh_tempo;
        }
        $tes = Carbon::now()->addDay();
        return $tes;
    }
}
