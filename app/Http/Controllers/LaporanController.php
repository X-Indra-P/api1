<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth')->only(['index']);
        $this->middleware('api')->only(['get_laporans']);
    }
    
    public function get_laporans(Request $request)
    {
        $laporan = DB::table('pesanan_details')
            ->join('barangs', 'barangs.id', '=', 'pesanan_details.id_barang')
            ->select(DB::raw('namabarang, harga, count(*) as jumlah_pembelian, SUM(total) as pendapatan, SUM(jumlah) as total_qty'))
            ->whereRaw("date(pesanan_details.created_at) >= '$request->dari'")
            ->whereRaw("date(pesanan_details.created_at) <= '$request->sampai'")
            ->groupBy('id_barang', 'namabarang', 'harga')
            ->get();

            return response()->json([
                'data' => $laporan
            ]);
    }

    public function index()
    {
        return view('laporan.index');
    }

    
}
