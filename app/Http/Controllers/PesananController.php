<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PesananController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth')->only(['list', 'dikonfirmasi_list', 'dikemas_list', 'dikirim_list', 'diterima_list', 'selesai_list']);
        $this->middleware('api')->only(['store', 'update', 'destroy', 'ubah_status', 'masuk', 'dikonfirmasi', 'dikemas', 'dikirim', 'diterima', 'selesai']);
    }

    public function list()
    {
        return view('pesanan.masuk');
    }

    public function dikonfirmasi_list()
    {
        return view('pesanan.dikonfirmasi');
    }

    public function dikemas_list()
    {
        return view('pesanan.dikemas');
    }

    public function dikirim_list()
    {
        return view('pesanan.dikirim');
    }

    public function diterima_list()
    {
        return view('pesanan.diterima');
    }

    public function selesai_list()
    {
        return view('pesanan.selesai');
    }

    /**
    *@return \Illuminate\Http\Response
     */
    public function index()
    {
        $pesanans = Pesanan::with('member')->get();

        return response()->json([
            'data' => $pesanans
        ]);
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
            'id_member' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();
        $pesanan = Pesanan::create($input);

        for ($i=0; $i < count($input['id_barang']); $i++) { 
            PesananDetail::create([
                'id_pesanan' => $input['id'],
                'id_barang' => $input['id_barang'][$i],
                'jumlah' => $input['jumlah'][$i],
                'size' => $input['size'][$i],
                'total' => $input['total'][$i],
            ]);
        }

        return response()->json([
            'data' => $pesanan
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pesanan $pesanan)
    {
        return response()->json([
            'data' => $pesanan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pesanan $pesanan)
    {
        //
    }


    public function update(Request $request, Pesanan $pesanan)
    {
        $validator = Validator::make($request->all(), [
            'id_member' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();
        $pesanan->update($input);

        PesananDetail::where('id_pesanan', $pesanan['id'])->delete();

        for ($i=0; $i < count($input['id_barang']); $i++) { 
            PesananDetail::create([
                'id_pesanan' => $input['id'],
                'id_barang' => $input['id_barang'][$i],
                'jumlah' => $input['jumlah'][$i],
                'size' => $input['size'][$i],
                'total' => $input['total'][$i],
            ]);
        }

        return response()->json([
            'message' => 'success',
            'data' => $pesanan
        ]);
    }

    public function ubah_status(Request $request, Pesanan $pesanan)
    {
        $pesanan->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $pesanan
        ]);
    }

    public function masuk()
    {
        $pesanans = Pesanan::with('member')->where('status', 'Masuk')->get();

        return response()->json([
            'data' => $pesanans
        ]);
    }

    public function dikonfirmasi()
    {
        $pesanans = Pesanan::with('member')->where('status', 'Dikonfirmasi')->get();

        return response()->json([
            'data' => $pesanans
        ]);
    }

    public function dikemas()
    {
        $pesanans = Pesanan::with('member')->where('status', 'Dikemas')->get();

        return response()->json([
            'data' => $pesanans
        ]);
    }

    public function dikirim()
    {
        $pesanans = Pesanan::with('member')->where('status', 'Dikirim')->get();

        return response()->json([
            'data' => $pesanans
        ]);
    }

    public function diterima()
    {
        $pesanans = Pesanan::with('member')->where('status', 'Diterima')->get();

        return response()->json([
            'data' => $pesanans
        ]);
    }

    public function selesai()
    {
        $pesanans = Pesanan::with('member')->where('status', 'Selesai')->get();

        return response()->json([
            'data' => $pesanans
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pesanan $pesanan)
    {
        $pesanan->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
