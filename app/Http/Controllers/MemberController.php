<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class MemberController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api') -> except(['index']);
    }
    /**
    *@return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = Member::all();

        return response()->json([
            'data' => $members
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
            'nama_member' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'detail_alamat' => 'required',
            'no_hp' => 'required',
            'email' => 'required',
            'password' => 'required|same:konfirmasi_password',
            'konfirmasi_password' => 'required|same:password'
        ]);

        if ($validator->fails()){
            return response()->json(
                $validator->errors(), 422
            );
        }

        $input = $request->all();
        $input['password'] = bcrypt($request->password);
        unset($input['konfirmasi_password']);
        $member = Member::create($input);

        return response()->json([
            'data' => $member
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return response()->json([
            'data' => $member
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $member->update($request->all());

        return response()->json([
            'message' => 'Sukses',
            'data' => $member
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return response()->json([
            'message' => 'Sukses'
        ]);
    }
}
