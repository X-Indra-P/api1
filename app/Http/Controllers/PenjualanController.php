<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
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
        $penjualans = Penjualan::with('Member')->get();

        return response()->json([
            'data' => $penjualans
        ]);
    }

    public function list()
    {
        $members = Member::all();
        return view('penjualan.index', compact('members'));
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
            'nomortransaksi' => 'required',
            'totalharga' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();

        $penjualan = Penjualan::create($input);

        return response()->json([
            'success' => true,
            'data' => $penjualan
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Penjualan $penjualan)
    {
        return response()->json([
            'data' => $penjualan
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penjualan $penjualan)
    {
        //
    }


    public function update(Request $request, Penjualan $penjualan)
    {
        $validator = Validator::make($request->all(), [
            'nomortransaksi' => 'required',
            'totalharga' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();

        $penjualan->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $penjualan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        $penjualan->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
