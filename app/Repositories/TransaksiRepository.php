<?php

namespace App\Repositories;

use App\Models\Transaksi;

class TransaksiRepository
{
    private Transaksi $transaksi;
	public function __construct()
	{
		$this->transaksi = new Transaksi();
	}
    public function getAll() : Object
    {
        $transaksi = Transaksi::with('pelanggan')->get();
        return $transaksi;
    }
    public function getById($id)
	{
		$task = Transaksi::with('pelanggan')->get();
		return $task;
	}
    public function store($data) : Object
    {
        $dataBaru = new $this->transaksi;
        $dataBaru->no_tagihan = $data['no_tagihan'];
        $dataBaru->produk = $data['produk'];
        $dataBaru->jatuh_tempo = $data['jatuh_tempo'];
        $dataBaru->jumlah_tagihan = $data['jumlah_tagihan'];
        $dataBaru->status_tagihan = $data['status_tagihan'];
        $dataBaru->pelanggan_id = $data['pelanggan_id'];
        $dataBaru->save();
        return $dataBaru->fresh();
    }
    public function update($data, $id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->update($data);
    }
    public function delete($id)
	{
        $transaksi = Transaksi::find($id);
		$transaksi->delete();
	}
    public function save(Transaksi $transaksi, array $data)
    {
        $transaksi->update($data);
        return $transaksi;
    }
}
