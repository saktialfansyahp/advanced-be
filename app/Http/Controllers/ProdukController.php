<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index(){
        $data = Produk::all();
        return response()->json([
            'produk' => $data,
        ], 200);
    }
    public function getById($id){
        $data = Produk::find($id);
        return response()->json([
            'produk' => $data
        ]);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'harga' => 'required|integer',
        ]);

        // Return an error response if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new user record
        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
        ]);
        // Return a success response with the JWT token
        return response()->json([
            'produk' => $produk,
        ], 201);
    }
    public function update(Request $request, $id){
        $data = $request->all();
        $validator = Validator::make($data, [
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
        ]);
        if ($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $produk = Produk::find($id);
        $produk->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Produk updated successfully',
            'data' => $produk,
        ], 203);
    }
    public function destroy($id){
        $produk = Produk::find($id);
        $produk->delete();
    }
}
