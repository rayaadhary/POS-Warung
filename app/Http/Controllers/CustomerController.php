<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pelanggan.index');
    }

    public function data()
    {
        $pelanggan = Customer::orderBy('kode_customers')->get();
        return datatables()
            ->of($pelanggan)
            ->addIndexColumn()
            ->addColumn('kode_customers', function ($pelanggan) {
                return '<span class="label label-success">' . $pelanggan->kode_customers . '</span>';
            })
            ->addColumn('aksi', function ($pelanggan) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'  . route('pelanggan.update', $pelanggan->id_customers)  . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'  . route('pelanggan.destroy', $pelanggan->id_customers)  . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_customers'])
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
        $customers = Customer::latest()->first();

        $id = (int)$customers->id_customers + 1 ?? 1;
        $request['kode_customers'] = 'C' . tambah_nol_didepan($id, 6);

        Customer::create($request->all());
        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pelanggan = Customer::find($id);
        return response()->json($pelanggan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Customer::find($id)->update([
            'nama_customers' => $request->nama_customers
        ]);
        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Customer::find($id)->delete();
        return response(null, 204);
    }
}
