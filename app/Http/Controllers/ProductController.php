<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Category::all()->pluck('nama', 'id_categories');
        return view('produk.index', compact('kategori'));
    }

    public function data()
    {
        $produk = Product::leftJoin('categories', 'categories.id_categories', 'products.id_categories')
            ->select('products.*', 'nama')
            ->get();

        return datatables()
            ->of($produk)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($produk) {
                return '<span class="label label-success">' . $produk->kode_produk . '</span>';
            })
            ->addColumn('harga_beli', function ($produk) {
                return formatUang($produk->harga_beli);
            })
            ->addColumn('harga_jual', function ($produk) {
                return formatUang($produk->harga_jual);
            })
            ->addColumn('stok', function ($produk) {
                return formatUang($produk->stok);
            })
            ->addColumn('aksi', function ($produk) {
                return '
            <div class="btn-group">
                <button onclick="editForm(`'  . route('produk.update', $produk->id_products)  . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                <button onclick="deleteData(`'  . route('produk.destroy', $produk->id_products)  . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            </div>
            ';
            })
            ->rawColumns(['aksi', 'kode_produk', 'select_all'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $produk = Product::latest()->first() ?? new Product();
        $request['kode_produk'] = 'P' . tambah_nol_didepan((int)$produk->id_products + 1, 6);

        Product::create($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk = Product::find($id);
        return response()->json($produk);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Product::find($id)->update($request->all());
        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_products as $id) {
            Product::find($id)->delete();
        }

        return response(null, 204);
    }
}
