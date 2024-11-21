<?php

namespace App\Http\Controllers;

use App\Models\PosJuri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use RealRashid\SweetAlert\Facades\Alert;

class PosJuriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        confirmDelete("Hapus Pos Juri", "Apakah anda yakin untuk menghapus data ini?");
        return view('admin.pos_juri.index', [
            'data' => PosJuri::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.pos_juri.formulir', [
            'next' => 'store',
            'juri' => User::where('gid', 2)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $reqData = $request->only('judul', 'user_id');
        $reqData['id_lomba'] = implode(',', $request->id_lomba);

        $validator = Validator::make($reqData, [
            'judul' => 'required|unique:pos_juris,judul',
        ],[
            'judul.required' => 'Judul Pos Juri tidak boleh kosong',
            'judul.unique' => 'Judul Pos Juri telah terdaftar',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        PosJuri::create($reqData);
        return redirect('pos_juri')->withSuccess('Data Pos Juri berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PosJuri  $posJuri
     * @return \Illuminate\Http\Response
     */
    public function show(PosJuri $posJuri)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PosJuri  $posJuri
     * @return \Illuminate\Http\Response
     */
    public function edit(PosJuri $posJuri, $id)
    {
        //
        return view('admin.pos_juri.formulir', [
            'data' => $posJuri->find($id),
            'next' => 'update',
            'juri' => User::where('gid', 2)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PosJuri  $posJuri
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PosJuri $posJuri)
    {
        $id = $request->id;
        $reqData = $request->only('judul', 'user_id');
        $reqData['id_lomba'] = implode(',', $request->id_lomba);

        $validator = Validator::make($reqData, [
            'judul' => 'required|unique:pos_juris,judul,'.$id,
        ],[
            'judul.required' => 'Judul Pos Juri tidak boleh kosong',
            'judul.unique' => 'Judul Pos Juri telah terdaftar',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        
        $posJuri->find($id)->update($reqData);
        return redirect('pos_juri')->withSuccess('Data Pos Juri berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PosJuri  $posJuri
     * @return \Illuminate\Http\Response
     */
    public function destroy(PosJuri $posJuri, $id)
    {
        //
        $posJuri->find($id)->delete();
        return redirect('pos_juri')->withSuccess('Data Pos Juri berhasil dihapus');
    }
}
