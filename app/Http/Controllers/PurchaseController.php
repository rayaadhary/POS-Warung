<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $supplier = Supplier::orderBy('nama')->get();
        return view('pembelian.index', compact('supplier'));
    }

    public function data()
    {
        $pembelian = Purchase::orderBy('id_purchases', 'desc')->get();

        return datatables()
            ->of($pembelian)
            ->addIndexColumn()
            ->addColumn('total_item', function ($pembelian) {
                return formatUang($pembelian->total_item);
            })
            ->addColumn('total_harga', function ($pembelian) {
                return 'Rp. ' . formatUang($pembelian->total_harga);
            })
            ->addColumn('bayar', function ($pembelian) {
                return 'Rp. ' . formatUang($pembelian->bayar);
            })
            ->addColumn('tanggal', function ($pembelian) {
                return tanggalIndonesia($pembelian->created_at, false);
            })
            ->addColumn('supplier', function ($pembelian) {
                return $pembelian->supplier->nama;
            })
            ->editColumn('diskon', function ($pembelian) {
                return $pembelian->diskon . '%';
            })
            ->addColumn('aksi', function ($pembelian) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`' . route('pembelian.show', $pembelian->id_purchases) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`' . route('pembelian.destroy', $pembelian->id_purchases) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create($id)
    {
        $pembelian = new Purchase();
        $pembelian->id_suppliers = $id;
        $pembelian->total_item = 0;
        $pembelian->total_harga = 0;
        $pembelian->diskon = 0;
        $pembelian->bayar = 0;
        $pembelian->save();

        session(['id_purchases' => $pembelian->id_purchases]);
        session(['id_suppliers' => $pembelian->id_suppliers]);

        return redirect()->route('pembelian_detail.index');
    }

    public function store(Request $request)
    {
        $pembelian = Purchase::findOrFail($request->id_purchases);
        $pembelian->total_harga = $request->total;
        $pembelian->total_item = $request->total_item;
        $pembelian->diskon = $request->diskon;
        $pembelian->bayar = $request->bayar;
        $pembelian->update();

        $detail = PurchaseDetail::where('id_purchases', $pembelian->id_purchases)->get();
        foreach ($detail as $item) {
            $produk = Product::find($item->id_products);
            $produk->stok += $item->jumlah;
            $produk->update();
        }

        return redirect()->route('pembelian.index');
    }


    public function show($id)
    {
        $detail = PurchaseDetail::with('produk')->where('id_purchases', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">' . $detail->produk->kode_produk . '</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_beli', function ($detail) {
                return 'Rp. ' . formatUang($detail->harga_beli);
            })
            ->addColumn('jumlah', function ($detail) {
                return formatUang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. ' . formatUang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $pembelian = Purchase::find($id);
        $detail    = PurchaseDetail::where('id_purchases', $pembelian->id_purchases)->get();
        foreach ($detail as $item) {
            $produk = Product::find($item->id_products);
            if ($produk) {
                $produk->stok -= $item->jumlah;
                $produk->update();
            }
            $item->delete();
        }

        $pembelian->delete();

        return response(null, 204);
    }
}
