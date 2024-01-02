<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Testimoni;
use App\Models\Barang;
use App\Models\About;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Keranjang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomepageController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        $categories = Category::all();
        $testimonis = Testimoni::all();
        $barangs = Barang::skip(0)->take(8)->get();

        return view('homepage.index', compact('sliders', 'categories', 'testimonis', 'barangs'));
    }

    public function barangs($id_subcategory)
    {
        $barangs = Barang::where('id_subkategori', $id_subcategory)->get();
        dd($barangs);
        return view('homepage.barangs', compact('barangs'));
    }

    public function barang($id_barang)
    {
        if (!Auth::guard('webmember')->user()) {
            return redirect('/login_member');
        }

        $barang = Barang::find($id_barang);
        $latest_barangs = Barang::orderByDesc('created_at')->offset(0)->limit(8)->get();

        return view('homepage.barang', compact('barang', 'latest_barangs'));
    }

    public function add_to_keranjang(Request $request)
    {
        $input = $request->all();
        Keranjang::create($input);
    }

    public function keranjang()
    {
        if (!Auth::guard('webmember')->user()) {
            return redirect('/login_member');
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 7bdcbd1f86cf3fc070203dc20256646b"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }

        $provinsi = json_decode($response);
        $keranjangs = Keranjang::where('id_member', Auth::guard('webmember')->user()->id)->where('is_checkout', 0)->get();
        $keranjang_total = Keranjang::where('id_member', Auth::guard('webmember')->user()->id)->where('is_checkout', 0)->sum('total');
        return view('homepage.keranjang', compact ('keranjangs', 'provinsi', 'keranjang_total'));
    }

    public function get_kota($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: 7bdcbd1f86cf3fc070203dc20256646b"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function get_ongkir($destination, $weight)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=17&destination=" . $destination . "&weight=" . $weight . "&courier=jne",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: 7bdcbd1f86cf3fc070203dc20256646b"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function delete_from_keranjang(Keranjang $keranjang)
    {
        $keranjang->delete();
        return redirect('/keranjang');
    }

    public function checkout()
    {
        $about = About::first();
        $pesanans = Pesanan::where('id_member', Auth::guard('webmember')->user()->id)->first();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: e3171a515518ac6f5e817c3322879d97"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }

        $provinsi = json_decode($response);
        return view('homepage.checkout', compact('about', 'pesanans', 'provinsi'));
    }

    public function checkout_pesanans(Request $request)
    {
        $id = DB::table('pesanans')->insertGetId([
            'id_member' => $request->id_member,
            'invoice' => date('ymds'),
            'grand_total' => $request->grand_total,
            'status' => 'Masuk',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        for ($i = 0; $i < count($request->id_produk); $i++) {
            DB::table('pesanan_details')->insert([
                'id_pesanan' => $id,
                'id_barang' => $request->id_produk[$i],
                'jumlah' => $request->jumlah[$i],
                'size' => $request->size[$i],
                'total' => $request->total[$i],
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
 

        Keranjang::where('id_member', Auth::guard('webmember')->user()->id)->update([
            'is_checkout' => 1
        ]);
    }

    public function pembayarans(Request $request)
    {
        Pembayaran::create([
            'id_pesanan' => $request->id_pesanan,
            'id_member' => Auth::guard('webmember')->user()->id,
            'jumlah' => $request->jumlah,
            'provinsi' => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => "",
            'detail_alamat' => $request->detail_alamat,
            'status' => 'MENUNGGU',
            'no_rekening' => $request->no_rekening,
            'atas_nama' => $request->atas_nama,
        ]);

        return redirect('/pesanans');
    }


    public function pesanans()
    {
        $pesanans = Pesanan::where('id_member', Auth::guard('webmember')->user()->id)->get();
        $pembayarans = Pembayaran::where('id_member', Auth::guard('webmember')->user()->id)->get();
        return view('homepage.pesanans', compact('pesanans', 'pembayarans'));
    }

    public function pesanan_selesai(Pesanan $pesanan)
    {
        $pesanan->status = 'Selesai';
        $pesanan->save();

        return redirect('/pesanans');
    }

    public function about()
    {
        $about = About::first();
        $testimonis = Testimoni::all();
        return view('homepage.about', compact('about', 'testimonis'));
    }

    public function contact()
    {
        $about = About::first();
        return view('homepage.contact', compact('about'));
    }

    public function faq()
    {
        return view('homepage.faq');
    }
}
