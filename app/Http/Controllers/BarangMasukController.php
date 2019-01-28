<?php

namespace App\Http\Controllers;

use App\BarangMasuk;
use App\StokBarang;
use Illuminate\Http\Request;


class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return BarangMasuk[]|\Illuminate\Database\Eloquent\Collection
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
            'id' => 'required',
            'jmlhMasuk' => 'required'
        ]);
//        $BM = BarangMasuk::create($request->all());
        $stok = StokBarang::find($request->id);
        $jumlah = $request->jmlhMasuk + $stok->stok;
        $stok->update([
            'stok' => $jumlah
        ]);
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
        $stok = StokBarang::find($id);
        if (count($stok) > 0)
            return response()->json($stok);

        return response()->json(['error', 'Barang tidak ditemukan'], 404);
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
//        $BM = BarangMasuk::find($id);
//        $stok = StokBarang::find($BM->barangId);
//
//        $stokTemp = ($stok->stok) - ($BM->jmlhMasuk);
//        $stokBaru = ($request->jmlhMasuk) + $stokTemp;

        $stok = StokBarang::find($id);
        $stok->update($request->all());

//        $stok->update([
//            'stok' => $stokBaru
//        ]);

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
        } catch (\Exception $e) {
            return response(['Delete Problems : ' . $e], 500);
        }

    }
}
