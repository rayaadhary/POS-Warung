<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        return view('pengeluaran.index');
    }

    public function data()
    {
        $pengeluaran = Expense::orderBy('id_expenses', 'desc')->get();
        return datatables()
            ->of($pengeluaran)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($pengeluaran) {
                return tanggalIndonesia($pengeluaran->tanggal);
            })
            ->addColumn('aksi', function ($pengeluaran) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'  . route('pengeluaran.update', $pengeluaran->id_expenses)  . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'  . route('pengeluaran.destroy', $pengeluaran->id_expenses)  . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
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
        Expense::create($request->all());
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
        $pengeluaran = Expense::find($id);
        return response()->json($pengeluaran);
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
        Expense::find($id)->update($request->all());
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
        Expense::find($id)->delete();
        return response(null, 204);
    }
}
