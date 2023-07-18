<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class SalesController extends Controller
{
    public function index()
    {
        return view('penjualan.index');
    }

    public function data()
    {
        $penjualan = Sales::orderBy('id_sales', 'desc')->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_item', function ($penjualan) {
                return formatUang($penjualan->total_item);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. ' . formatUang($penjualan->total_harga);
            })
            ->addColumn('bayar', function ($penjualan) {
                return 'Rp. ' . formatUang($penjualan->bayar);
            })
            ->addColumn('tanggal', function ($penjualan) {
                return tanggalIndonesia($penjualan->created_at, false);
            })
            ->editColumn('diskon', function ($penjualan) {
                return $penjualan->diskon . '%';
            })
            ->editColumn('kasir', function ($penjualan) {
                return $penjualan->user->name ?? '';
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`' . route('penjualan.show', $penjualan->id_sales) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`' . route('penjualan.destroy', $penjualan->id_sales) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $penjualan = new Sales();
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->save();
        session(['id_sales' => $penjualan->id_sales]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request)
    {
        $penjualan = Sales::findOrFail($request->id_sales);
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->diskon = $request->diskon;
        $penjualan->bayar = $request->bayar;
        $penjualan->diterima = $request->diterima;
        $penjualan->update();

        $detail = SalesDetail::where('id_sales', $penjualan->id_sales)->get();
        foreach ($detail as $item) {
            $item->diskon = $request->diskon;
            $item->update();

            $produk = Product::find($item->id_products);
            $produk->stok -= $item->jumlah;
            $produk->update();
        }

        return redirect()->route('transaksi.selesai');
    }

    public function show($id)
    {
        $detail = SalesDetail::with('produk')->where('id_sales', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">' . $detail->produk->kode_produk . '</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'Rp. ' . formatUang($detail->harga_jual);
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
        $penjualan = Sales::find($id);
        $detail    = SalesDetail::where('id_sales', $penjualan->id_sales)->get();
        foreach ($detail as $item) {
            $produk = Product::find($item->id_products);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        return view('penjualan.selesai');
    }

    public function notaKecil()
    {
        $penjualan = Sales::find(session('id_sales'));
        if (!$penjualan) {
            abort(404);
        }
        $detail = SalesDetail::with('produk')
            ->where('id_sales', session('id_sales'))
            ->get();

        return view('penjualan.nota_kecil', compact('penjualan', 'detail'));
    }


    public function notaBesar()
    {
        $penjualan = Sales::find(session('id_sales'));
        if (!$penjualan) {
            abort(404);
        }
        $detail = SalesDetail::with('produk')
            ->where('id_sales', session('id_sales'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('penjualan', 'detail'));
        $pdf->setPaper(0, 0, 609, 440, 'potrait');
        return $pdf->stream('Transaksi-' . date('Y-m-d-his') . '.pdf');
    }
}
