<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class PembayaranController extends Controller
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
        $pembayarans = pembayaran::with('Pesanan')->get();

        return response()->json([
            'data' => $pembayarans
        ]);
    }

    public function list()
    {
        $pesanans = Pesanan::all();
        return view('pembayaran.index', compact('pesanans'));
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
            'tanggal' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();

        $pembayaran = Pembayaran::create($input);

        return response()->json([
            'success' => true,
            'data' => $pembayaran
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        return response()->json([
            'data' => $pembayaran
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        //
    }


    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }


        $pembayaran->update([
            'status' => request('status')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $pembayaran
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        File::delete('uploads/' . $pembayaran->gambar);
        $pembayaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
