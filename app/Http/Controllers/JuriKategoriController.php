<?php

namespace App\Http\Controllers;

use App\Models\JuriKategori;
use Illuminate\Http\Request;

class JuriKategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $user_id = $request->user_id;
        $data = [];
        
        JuriKategori::where('user_id', $user_id)->delete();

        if($request->id_lomba){
            foreach ($request->id_lomba as $key => $value) {
                $data[] = ['user_id' => $user_id, 'id_lomba' => $value];
            }

            JuriKategori::insert($data);    
        }
        
        return back()->withSuccess('Data Kategori Lomba berhasil diupdate');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JuriKategori  $juriKategori
     * @return \Illuminate\Http\Response
     */
    public function show(JuriKategori $juriKategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JuriKategori  $juriKategori
     * @return \Illuminate\Http\Response
     */
    public function edit(JuriKategori $juriKategori)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JuriKategori  $juriKategori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JuriKategori $juriKategori)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JuriKategori  $juriKategori
     * @return \Illuminate\Http\Response
     */
    public function destroy(JuriKategori $juriKategori)
    {
        //
    }
}
