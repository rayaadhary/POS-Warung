<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function index()
    {
        return view('pengguna.index');
    }

    public function data()
    {
        $pengguna = User::orderBy('id', 'desc')->get();
        return datatables()
            ->of($pengguna)
            ->addIndexColumn()
            ->addColumn('aksi', function ($pengguna) {
                return '
                <div class="btn-group">
                    <button onclick="editForm(`'  . route('pengguna.update', $pengguna->id)  . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button onclick="deleteData(`'  . route('pengguna.destroy', $pengguna->id)  . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
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
        $password = $request->password;
        $password_confirmation = $request->password_confirmation;

        if ($password != $password_confirmation) {
            return response()->json('Password tidak sesuai', 400);
        } else {
            $pengguna = new User();
            $pengguna->name = $request->name;
            $pengguna->email = $request->email;
            $pengguna->password =  bcrypt($password);
            $pengguna->save();

            return response()->json('Data berhasil disimpan', 200);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pengeluaran = User::find($id);
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
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password') && $request->password != "")
            $user->password = bcrypt($request->password);
        $user->update();

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
        User::find($id)->delete();
        return response(null, 204);
    }
}
