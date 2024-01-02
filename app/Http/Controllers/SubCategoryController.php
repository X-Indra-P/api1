<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SubCategoryController extends Controller
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
        $subcategories = SubCategory::with('Category')->get();

        return response()->json([
            'data' => $subcategories
        ]);
    }

    public function list()
    {
        $categories = Category::all();
        return view('subkategori.index', compact('categories'));
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
            'nama_subkategori' => 'required',
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
            $nama_gambar = time() . rand(1,9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('uploads'), $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }

        $subcategory = SubCategory::create($input);

        return response()->json([
            'success' => true,
            'data' => $subcategory
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subcategory)
    {
        return response()->json([
            'data' => $subcategory
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subcategory)
    {
        //
    }


    public function update(Request $request, SubCategory $subcategory)
    {
        $validator = Validator::make($request->all(), [
            'nama_subkategori' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();

        if ($request->has('gambar')) {
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1,9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('uploads'), $nama_gambar);
            $input['gambar'] = $nama_gambar;
        } else {
            unset($input[$gambar]);
        }

        $subcategory->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $subcategory
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subcategory)
    {
        File::delete('uploads/' . $subcategory->gambar);
        $subcategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
