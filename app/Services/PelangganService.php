<?php

namespace App\Services;

use App\Repositories\PelangganRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class PelangganService{
    private PelangganRepository $pelangganRepository;
	public function __construct()
	{
		$this->pelangganRepository = new PelangganRepository('pelanggan');
	}
    public function getAll()
    {
        $pelanggan = $this->pelangganRepository->getAll();
        return $pelanggan;
    }
    public function getById($id)
	{
		$task = $this->pelangganRepository->getById($id);
		return $task;
	}
    public function store($data) : Object
    {
        $validator = Validator::make($data, [
            'no_telp' => 'required',
            'kota' => 'required',
            'status' => 'required',
            'jenis' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }
        $result = $this->pelangganRepository->store($data);
        return $result;
    }
    public function update($id, $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'no_telp' => 'required',
            'kota' => 'required',
            'alamat' => 'required',
            'email' => 'required',
            'status' => 'required',
            'jenis' => 'required'
        ]);
        if ($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }
        $pelanggan = $this->pelangganRepository->getById($id);
        if (!$pelanggan) {
            return response()->json(['error' => 'Pelanggan not found'], 404);
        }
        $this->pelangganRepository->update($data, $id);
        return $pelanggan->fresh();
    }
    public function delete($id)
    {
        if(!$id)
		{
			return response()->json([
				'error' => 'Pelanggan not found'
			], 404);
		}
        $task = $this->pelangganRepository->delete($id);
        return $task;
    }
}
