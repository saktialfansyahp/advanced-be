<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\PelangganService;

class PelangganController extends Controller
{
    private PelangganService $pelangganService;
	public function __construct() {
		$this->pelangganService = new PelangganService();
	}

    public function byId($id)
    {
        try {
            $result = $this->pelangganService->getById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result);
    }

	public function displayPelanggan()
	{
		try {
            $result = $this->pelangganService->getAll();
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result);
	}

	public function createPelanggan(Request $request)
	{
        $data = $request->all();

        $result = ['status' => 201];

        try {
            $result['data'] = $this->pelangganService->store($data);
        } catch (Exception $e) {
            $result = [
                'status' =>'422',
                'error' => $e->getMessage(),
            ];
        }
        return response()->json($result, $result['status']);
	}

	public function updatePelanggan(Request $request, $id)
	{
		$data = $request->all();

        $updatedPelanggan = $this->pelangganService->update($id, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Pelanggan updated successfully',
            'data' => $updatedPelanggan,
        ], 200);
	}


	public function deletePelanggan($id)
	{
        $this->pelangganService->delete($id);

		return response()->json([
			'message'=> 'Success delete pelanggan '.$id
		]);
	}
}
