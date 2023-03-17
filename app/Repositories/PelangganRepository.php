<?php

namespace App\Repositories;

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
        $pelanggan = Pelanggan::get();
        return $pelanggan;
    }
    public function getById($id)
	{
		$task = Pelanggan::find($id);
		return $task;
	}
    public function store($data) : Object
    {
        $dataBaru = new $this->pelanggan;
        $dataBaru->name = $data['name'];
        $dataBaru->no_telp = $data['no_telp'];
        $dataBaru->kota = $data['kota'];
        $dataBaru->alamat = $data['alamat'];
        $dataBaru->email = $data['email'];
        $dataBaru->status = $data['status'];
        $dataBaru->jenis = $data['jenis'];
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
        $pelanggan = Pelanggan::find($id);
		$pelanggan->delete();
	}
    public function save(Pelanggan $pelanggan, array $data)
    {
        $pelanggan->update($data);
        return $pelanggan;
    }
}
