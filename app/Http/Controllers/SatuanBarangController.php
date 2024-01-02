<?php

namespace App\Http\Controllers;

use App\Models\SatuanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SatuanBarangController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth')->only(['list']);
        $this->middleware('api')->only(['store', 'update', 'destroy']);
    }

    public function list()
    {
        return view('satuanbarang.index');
    }

    /**
    *@return \Illuminate\Http\Response
     */
    public function index()
    {
        $satuanbarangs = SatuanBarang::all();

        return response()->json([
            'data' => $satuanbarangs
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
            'satuan' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();
        $satuanbarang = SatuanBarang::create($input);

        return response()->json([
            'success' => true,
            'data' => $satuanbarang
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SatuanBarang $satuanbarang)
    {
        return response()->json([
            'data' => $satuanbarang
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SatuanBarang $satuanbarang)
    {
        //
    }


    public function update(Request $request, SatuanBarang $satuanbarang)
    {
        $validator = Validator::make($request->all(), [
            'satuan' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }
        $input = $request->all();
        $satuanbarang->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $satuanbarang
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SatuanBarang $satuanbarang)
    {
        $satuanbarang->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
