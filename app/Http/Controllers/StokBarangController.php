<?php

namespace App\Http\Controllers;

use App\StokBarang;
use Illuminate\Http\Request;
use Mockery\Exception;

class StokBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return StokBarang[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return StokBarang::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'namabarang' => 'required',
            'stok' => 'required'
        ]);
        $stok = StokBarang::create($request->all());
        return $stok;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stokbarang = StokBarang::find($id);

        if (count($stokbarang) > 0)
            return response()->json($stokbarang);

        return response()->json(['error' => 'Barang tidak ditemukan'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $stok = StokBarang::find($id);

        $stok->update($request->all());

        return response()->json($stok);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            StokBarang::destroy($id);
            return response([], 204);
        } catch (Exception $e) {
            return response(['Delete Problem : ' . $e], 500);
        }
    }
}
