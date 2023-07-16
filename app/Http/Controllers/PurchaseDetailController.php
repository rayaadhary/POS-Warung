<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;

class PurchaseDetailController extends Controller
{
    public function index()
    {
        $id_purchases = session('id_purchases');
        $produk = Product::orderBy('nama_produk')->get();
        $supplier = Supplier::find(session('id_suppliers'));
        $diskon = Purchase::find($id_purchases)->diskon ?? 0;


        if (!$supplier) {
            abort(404);
        }

        return view('pembelian_detail.index', compact('id_purchases', 'produk', 'supplier', 'diskon'));
    }

    public function data($id)
    {
        $detail = PurchaseDetail::with('produk')
            ->where('id_purchases', $id)
            ->get();


        $data = array();
        $total = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">' . $item->produk['kode_produk'] . '</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_beli']  = 'Rp. ' . formatUang($item->harga_beli);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->id_purchases_detail . '" value="' . $item->jumlah . '">';
            $row['subtotal']    = 'Rp. ' . formatUang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`' . route('pembelian_detail.destroy', $item->id_purchases_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_beli * $item->jumlah;
            $total_item += $item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">' . $total . '</div>
                <div class="total_item hide">' . $total_item . '</div>',
            'nama_produk' => '',
            'harga_beli'  => '',
            'jumlah'      => '',
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

        $detail = new PurchaseDetail();
        $detail->id_purchases = $request->id_purchases;
        $detail->id_products = $produk->id_products;
        $detail->harga_beli = $produk->harga_beli;
        $detail->jumlah = 1;
        $detail->subtotal = $produk->harga_beli;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PurchaseDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_beli * $request->jumlah;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = PurchaseDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon, $total)
    {
        $bayar = $total - ($diskon / 100 * $total);
        $data  = [
            'totalrp' => formatUang($total),
            'bayar' => $bayar,
            'bayarrp' => formatUang($bayar),
            'terbilang' => ucwords(terbilang($bayar) . ' Rupiah')
        ];

        return response()->json($data);
    }
}
