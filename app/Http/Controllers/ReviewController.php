<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ReviewController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->only(['list']);
        $this->middleware('api')->only(['store', 'update', 'destroy']);
    }

    public function list()
    {
        return view('review.index');
    }

    /**
    *@return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = Review::all();

        return response()->json([
            'data' => $reviews
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
            'id_member' => 'required',
            'kode_produk' => 'required',
            'nama_produk' => 'required',
            'review' => 'required',
            'rating' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();

        $review = Review::create($input);

        return response()->json([
            'success' => true,
            'data' => $review
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        return response()->json([
            'data' => $review
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }


    public function update(Request $request, Review $review)
    {
        $validator = Validator::make($request->all(), [
            'id_member' => 'required',
            'kode_barang' => 'required',
            'nama_produk' => 'required',
            'review' => 'required',
            'rating' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();
        $review->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $review
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
