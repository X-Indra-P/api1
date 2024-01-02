<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Barang;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailPenjualanController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth')->only(['list']);
        $this->middleware('api')->only(['store', 'update', 'destroy']);
    }
    /**
    *@return \Illuminate\Http\Response
     */
    public function index()
    {
        $detailpenjualans = DetailPenjualan::with('Penjualan', 'Barang')->get();

        return response()->json([
            'data' => $detailpenjualans
        ]);
    }
    

    public function list()
    {
        $penjualans = Penjualan::all();
        $barangs = Barang::all();
        return view('detailpenjualan.index', compact('penjualans', 'barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
    *@param \Illuminate\Http\Request
    *@param \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kuantitas' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();

        $detailpenjualan = DetailPenjualan::create($input);

        return response()->json([
            'success' => true,
            'data' => $detailpenjualan
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(DetailPenjualan $detailpenjualan)
    {
        return response()->json([
            'data' => $detailpenjualan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetailPenjualan $detailpenjualan)
    {
        //
    }


    public function update(Request $request, DetailPenjualan $detailpenjualan)
    {
        $validator = Validator::make($request->all(), [
            'kuantitas' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();

        $detailpenjualan->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $detailpenjualan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetailPenjualan $detailpenjualan)
    {
        $detailpenjualan->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
