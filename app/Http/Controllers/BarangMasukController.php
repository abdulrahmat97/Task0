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
        return BarangMasuk::all();
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
            'barangId' => 'required',
            'jmlhMasuk' => 'required'
        ]);
        $BM = BarangMasuk::create($request->all());
        $stok = StokBarang::find($request->barangId);
        $jumlah = $request->jmlhMasuk + $stok->stok;
        $stok->update([
            'stok' => $jumlah
        ]);
        return $BM;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $BM = BarangMasuk::find($id);
        if (count($BM) > 0)
            return response()->json($BM);

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
        $BM = BarangMasuk::find($id);
        $stok = StokBarang::find($BM->barangId);

        $stokTemp = ($stok->stok) - ($BM->jmlhMasuk);
        $stokBaru = ($request->jmlhMasuk) + $stokTemp;

        $BM->update($request->all());

        $stok->update([
            'stok' => $stokBaru
        ]);

        return response()->json($BM);

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
            BarangMasuk::destroy($id);
            return response([], 204);
        } catch (\Exception $e) {
            return response(['Delete Problems : ' . $e], 500);
        }

    }
}
