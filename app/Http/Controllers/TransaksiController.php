<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Models\Transaksiproduk;
use App\Services\TransaksiService;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    private TransaksiService $transaksiService;
	public function __construct() {
		$this->transaksiService = new TransaksiService();
	}

    public function byId($id)
    {
        try {
            $result = $this->transaksiService->getById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result);
    }

	public function displayTransaksi()
	{
		try {
            $result = $this->transaksiService->getAll();
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result);
	}
	public function getInvoice()
	{
		$data = Transaksiproduk::with('produk')->with('transaksi')->get();
        return response()->json($data);
	}

	public function createTransaksi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_tagihan' => 'required|unique:transaksi,no_tagihan|regex:/^[A-Z]\d{2}$/',
            'jatuh_tempo' => 'required',
            'jumlah_tagihan' => 'required',
            'status_tagihan' => 'required',
            'pelanggan_id' => 'required',
            'user_id' => 'required',
            'produks' => 'required|array'
        ]);
        if ($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $transaksi = Transaksi::create([
            'no_tagihan' => $request->no_tagihan,
            'jatuh_tempo' => $request->jatuh_tempo,
            'jumlah_tagihan' => $request->jumlah_tagihan,
            'status_tagihan' => $request->status_tagihan,
            'pelanggan_id' => $request->pelanggan_id,
            'user_id' => $request->user_id,
        ]);

        $transaksiId = $transaksi->id;

        // Iterasi array produks dan membuat entri baru dalam tabel transaksi_produk
        foreach ($request->input('produks') as $produkData) {
            Transaksiproduk::create([
                'transaksi_id' => $transaksiId,
                'produk_id' => $produkData['produk_id'],
                'quantity' => $produkData['quantity'],
            ]);
        }

        return response()->json($transaksi);
    }


	public function updateTransaksi(Request $request, $id)
	{
		$data = $request->all();

        $updatedTransaksi = $this->transaksiService->update($id, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi updated successfully',
            'data' => $updatedTransaksi,
        ], 200);
	}


	public function deleteTransaksi($id)
	{
        $this->transaksiService->delete($id);

		return response()->json([
			'message'=> 'Success delete transaksi '.$id
		]);
	}
}
