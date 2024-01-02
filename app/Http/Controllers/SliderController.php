<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth')->only(['list']);
        $this->middleware('api')->only(['store', 'update', 'destroy']);
    }

    public function list()
    {
        $sliders = Slider::all();
        return view('slider.index', compact('sliders'));
    }

    /**
    *@return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::all();

        return response()->json([
            'success' => true,
            'data' => $sliders
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_slider' => 'required',
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

        $slider = Slider::create($input);

        return response()->json([
            'success' => true,
            'data' => $slider
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        return response()->json([
            'success' => true,
            'data' => $slider
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Slider $slider)
    {
        $validator = Validator::make($request->all(), [
            'nama_slider' => 'required',
            'deskripsi' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(
                $validator->errors(), 422
            );
        }
    
        $input = $request->all();
    
        if ($request->has('gambar')) { 
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->move(public_path('uploads'), $nama_gambar);
            $input['gambar'] = $nama_gambar;
        } else {
            unset($input['gambar']);
        }
    
        $slider->update($input);
    
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $slider
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        File::delete('uploads/' . $slider->gambar);
        $slider->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
    
}