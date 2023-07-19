<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesDetailController extends Controller
{
    public function index()
    {
        $produk = Product::orderBy('nama_produk')->get();
        $diskon = 0;

        // cek apakah ada transaksi yang sedang berjalan
        if ($id_sales = session('id_sales')) {
            return view('penjualan_detail.index', compact('produk', 'diskon', 'id_sales'));
        } else {
            return redirect()->route('transaksi.baru');
        }
        return;
    }

    public function data($id)
    {
        $detail = SalesDetail::with('produk')
            ->where('id_sales', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">' . $item->produk['kode_produk'] . '</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_jual']  = 'Rp. ' . formatUang($item->harga_jual);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->id_sales_detail . '" value="' . $item->jumlah . '">';
            $row['diskon']    = $item->diskon . '% ';
            $row['subtotal']    = 'Rp. ' . formatUang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`' . route('transaksi.destroy', $item->id_sales_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">' . $total . '</div>
                <div class="total_item hide">' . $total_item . '</div>',
            'nama_produk' => '',
            'harga_jual'  => '',
            'jumlah'      => '',
            'diskon'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Product::where('id_products', $request->id_products)->first();
        if (!$produk) {
            return response()->json('Data gagal disimpan', 400);
        }

        $detail = new SalesDetail();
        $detail->id_sales = $request->id_sales;
        $detail->id_products = $produk->id_products;
        $detail->harga_jual = $produk->harga_jual;
        $detail->jumlah = 1;
        $detail->diskon = $produk->diskon;
        $detail->subtotal = $produk->harga_jual - ($produk->diskon / 100 * $produk->harga_jual);;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = SalesDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_jual * $request->jumlah;
        $detail->update();
    }

    public function loadForm($diskon = 0, $total = 0, $diterima = 0)
    {
        $bayar   = $total - ($diskon / 100 * $total);
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data    = [
            'totalrp' => formatUang($total),
            'bayar' => $bayar,
            'bayarrp' => formatUang($bayar),
            'terbilang' => ucwords(terbilang($bayar) . ' Rupiah'),
            'kembalirp' => formatUang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali) . ' Rupiah'),
        ];

        return response()->json($data);
    }

    public function destroy($id)
    {
        SalesDetail::find($id)->delete();

        return response(null, 204);
    }
}
