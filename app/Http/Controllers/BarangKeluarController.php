<?php

namespace App\Http\Controllers;

use App\BarangKeluar;
use App\StokBarang;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return BarangKeluar[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return BarangKeluar::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
           'barangId' => 'required',
           'jmlhKeluar' => 'required'
        ]);
        $BK = BarangKeluar::create($request->all());
        $stok = StokBarang::find($request->barangId);
        $stokBaru = $stok->stok - $request->jmlhKeluar;
        $stok->update([
            'stok' => $stokBaru
        ]);
        return $BK;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $BK = BarangKeluar::find($id);

        if (count($BK)>0)
            return response()->json($BK);

        return response(['error','Barang tidak ditemukan'],404);
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
        $BK = BarangKeluar::find($id);
        $stok = StokBarang::find($BK->barangId);

        $stokTemp = ($stok->stok) + ($BK->jmlhKeluar);
        $stokbaru= $stokTemp - ($request->jmlhKeluar);

        $BK->update($request->all());

        $stok->update([
            'stok'=>$stokbaru
        ]);

        return response()->json($BK);
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
            BarangKeluar::destroy($id);
            return response([], 204);
        } catch (\Exception $e) {
            return response(['Delete error: ' . $e], 500);
        }
    }
}
