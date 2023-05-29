<?php

namespace App\Services;

use App\Repositories\TransaksiRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class TransaksiService{
    private TransaksiRepository $transaksiRepository;
	public function __construct()
	{
		$this->transaksiRepository = new TransaksiRepository('transaksi');
	}
    public function getAll()
    {
        $transaksi = $this->transaksiRepository->getAll();
        return $transaksi;
    }
    public function getById($id)
	{
		$task = $this->transaksiRepository->getById($id);
		return $task;
	}
    public function store($data) : Object
    {
        $validator = Validator::make($data, [
            'no_tagihan' => 'required|unique:transaksi,no_tagihan|regex:/^[A-Z]\d{2}$/',
            'produk' => 'required',
            'jatuh_tempo' => 'required',
            'jumlah_tagihan' => 'required',
            'status_tagihan' => 'required',
            'pelanggan_id' => 'required',
            'user_id' => 'required',
        ]);
        if ($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }
        $result = $this->transaksiRepository->store($data);
        return $result;
    }
    public function update($id, $data)
    {
        $validator = Validator::make($data, [
            'status_tagihan' => 'required',
        ]);
        if ($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }
        $transaksi = $this->transaksiRepository->getById($id);
        if (!$transaksi) {
            return response()->json(['error' => 'Transaksi not found'], 404);
        }
        $this->transaksiRepository->update($data, $id);
        return $transaksi->fresh();
    }
    public function delete($id)
    {
        if(!$id)
		{
			return response()->json([
				'error' => 'Transaksi not found'
			], 404);
		}
        $task = $this->transaksiRepository->delete($id);
        return $task;
    }
}
