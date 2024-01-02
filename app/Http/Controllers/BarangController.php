<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SatuanBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth')->only(['list']);
        $this->middleware('api')->only(['store', 'update', 'destroy']);
    }

    public function list()
    {
        $satuanbarangs = SatuanBarang::all();
        $categories = Category::all();
        $subcategories = Subcategory::all();

        return view ('barang.index', compact('satuanbarangs', 'categories', 'subcategories'));
    }

    /**
    *@return \Illuminate\Http\Response
     */
    public function index()
    {
        $barangs = Barang::with('satuanbarang', 'category', 'subcategory')->get();

        return response()->json([
            'data' => $barangs
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
            'id_kategori' => 'required',
            'id_subkategori' => 'required',
            'kode' => 'required',
            'namabarang' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'satuan_id' => 'required',
            'ukuran' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();

        if ($request->has('gambar')) {
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('uploads', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }

        $barang = Barang::create($input);

        return response()->json([
            'success' => true,
            'data' => $barang
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        return response()->json([
            'data' => $barang
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        //
    }


    public function update(Request $request, Barang $barang)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required',
            'id_subkategori' => 'required',
            'kode' => 'required',
            'namabarang' => 'required',
            'harga' => 'required',
            'stok' => 'required',
            'satuan_id' => 'required',
            'ukuran' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();

        if ($request->has('gambar')) {
            File::delete('uploads/' . $barang->gambar);
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('uploads', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        } else {
            unset($input['gambar']);
        }

        $barang->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $barang
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(barang $barang)
    {
        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
