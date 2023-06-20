<?php

namespace App\Repositories;

use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\Transaksiproduk;

class TransaksiRepository
{
    private Transaksi $transaksi;
	public function __construct()
	{
		$this->transaksi = new Transaksi();
	}
    public function getAll() : Object
    {
        // $pelanggan = Transaksi::with('pelanggan')->get();
        $produk = Transaksiproduk::with('produk')->get();
        $data = Transaksi::with('pelanggan')->with('user')->with('transaksi_produk.produk')->get();
        // $data = $pelanggan->merge($user);
        $data = $data->sortBy(function ($item) {
        $number = substr($item->no_tagihan, 1);
            return intval($number);
        })->values()->reverse();

        return $data;
    }
    public function getById($id)
	{
		$task = Transaksi::with('pelanggan')->with('user')->where('no_tagihan', $id)->first();
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
        $dataBaru->user_id = $data['user_id'];
        $dataBaru->save();
        return $dataBaru->fresh();
    }
    public function update($data, $id)
    {
        $transaksi = Transaksi::where('no_tagihan', $id)->first();
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
