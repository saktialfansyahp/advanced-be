<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Services\TransaksiService;

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
        // $data = Transaksi::where('jatuh_tempo', Carbon::today())
        //                 ->where('status_tagihan', 'Belum Bayar')
        //                 ->get();
        // $transaksi = Transaksi::where('jatuh_tempo', Carbon::today())->where('status_tagihan', 'Belum Bayar')->with('pelanggan')->get();

        // // $data = array(
        // //     'subject'	=> 'Reminder',
        // //     'no_tagihan'=> $transaksi->no_tagihan,
        // //     'email'		=> $transaksi->email,
        // //     'name'	=> $transaksi->name,
        // //     //Send Request is send_feedback
        // //     'request'	=> 'send'
        // // );
        // foreach ($transaksi as $t) {
        //     $data = array(
        //         'subject'   => '$t->subject',
        //         'no_tagihan'=> $t->no_tagihan,
        //         'email'     => $t->pelanggan->email,
        //         'name'      => $t->pelanggan->name,
        //         //Send Request is send_feedback
        //         'request'   => 'send'
        //     );
        // return $data;
        // }
	}

	public function createTransaksi(Request $request)
	{
        $data = $request->all();

        $result = ['status' => 201];

        try {
            $result['data'] = $this->transaksiService->store($data);
        } catch (Exception $e) {
            $result = [
                'status' =>'422',
                'error' => $e->getMessage(),
            ];
        }
        return response()->json($result, $result['status']);
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
