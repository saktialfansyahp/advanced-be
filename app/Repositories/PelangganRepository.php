<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Pelanggan;

class PelangganRepository
{
    private Pelanggan $pelanggan;
	public function __construct()
	{
		$this->pelanggan = new Pelanggan();
	}
    public function getAll() : Object
    {
        $pelanggan = Pelanggan::with('user')->get();
        return $pelanggan;
    }
    public function getById($id)
	{
		$task = User::with('pelanggan')->find($id);
		return $task;
	}
    public function store($data) : Object
    {
        $dataBaru = new $this->pelanggan;
        $dataBaru->no_telp = $data['no_telp'];
        $dataBaru->kota = $data['kota'];
        $dataBaru->status = $data['status'];
        $dataBaru->jenis = $data['jenis'];
        $dataBaru->user_id = $data['user_id'];
        $dataBaru->save();
        return $dataBaru->fresh();
    }
    public function update($data, $id)
    {
        $pelanggan = Pelanggan::find($id);
        $pelanggan->update($data);
    }
    public function delete($id)
	{
        $user = User::find($id);
		$user->delete();
	}
    public function save(Pelanggan $pelanggan, array $data)
    {
        $pelanggan->update($data);
        return $pelanggan;
    }
}
