<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //

        confirmDelete("Hapus Data Pengguna", "Apakah anda yakin untuk menghapus data ini?");
        return view("admin.user.index", [
            'data' => User::orderBy('gid')->get(),
            'jnsJuri' => $this->jnsJuri,
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
        return view("admin.user.formulir",[
            'next' => 'store',
            'jnsJuri' => $this->jnsJuri,
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
        
        $reqData = $request->only('name', 'username', 'gid', 'aktif', 'foto');
        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }
        //dd($reqData);
        $reqData['password'] = Hash::make($request->password);
        $validator = Validator::make($reqData, [
            'name' => 'required|unique:users,name',
            'username' => 'required|unique:users,username',
            'foto' => 'sometimes|image|max:2048',
        ],[
            'name.required' => 'Nama Pengguna tidak boleh kosong',
            'name.unique' => 'Nama Pengguna telah terdaftar',

            'username.required' => 'Username Pengguna tidak boleh kosong',
            'username.unique' => 'Username Pengguna telah terdaftar',
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        
        if($request->hasFile('foto')){
            $file = $request->file('foto');    
            $path = $file->store('avatar', 'public');
            $reqData['foto'] = $path;
        }
        
        User::create($reqData);
        return redirect('user')->withSuccess('Data Pengguna berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user,$id)
    {
        //
        return view('admin.user.formulir', [
            'data'=> $user->find($id),
            'jnsJuri' => $this->jnsJuri,
            'next' => 'update',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $id = $request->id;
        $reqData = $request->only('name', 'username', 'gid', 'aktif');
        if(isset($reqData['aktif']) && $reqData['aktif'] == 'on'){
            $reqData['aktif'] = 1;
        }else{
            $reqData['aktif'] = 0;
        }

        $validator = Validator::make($reqData, [
            'name' => 'required|unique:users,name,'.$id,
            'username' => 'required|unique:users,username,'.$id,
            'foto' => 'sometimes|image|max:2048',
        ],[
            'name.required' => 'Nama Pengguna tidak boleh kosong',
            'name.unique' => 'Nama Pengguna telah terdaftar',

            'username.required' => 'Username Pengguna tidak boleh kosong',
            'username.unique' => 'Username Pengguna telah terdaftar',            
        ]);

        if($validator->fails())
        {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        if($request->hasFile('foto')){
            $file = $request->file('foto');    
            $path = $file->store('avatar', 'public');
            $reqData['foto'] = $path;
        }
        
        $user->find($id)->update($reqData);
        return redirect('user')->withSuccess('Data Pengguna berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, $id)
    {
        //
        $user->find($id)->delete();
        return redirect('user')->withSuccess('Data Pengguna berhasil dihapus');
    }
}
