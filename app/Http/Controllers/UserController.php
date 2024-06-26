<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //request $request untuk mengambil hasil inputan user
    public function authLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required'
        ]);
        //simpan data dari inputan hasil dan passwordnya ke dalam variable untuk memudahkan pemanggilannya
        $user = $request->only(['email', 'password']);
        //attempt : mengecek kecocokan email dan password kemudian menyimpannya ke dalam class Auth (memberi identitas data riwayat login ke projectnya)
        if (Auth::attempt($user)) {
            //perbedaan redirect() dan redirect()->route ??
            return redirect('/dashboard');
        } else {
            return redirect()->back()->with('failed', 'Login gagal! silahkan coba lagi!');
        }
    }
    public function index()
    {
        $user = User::orderBy('name', 'ASC')->simplePaginate(3);
        return view ('user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('user.create');
    }

  /**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    // Validasi
    $request->validate([
        'name' => 'required|min:3',
        'email' => 'required',
        'role' => 'required',
    ]);

    // Mendapatkan 3 karakter awal dari email dan nama
    //untuk mengambil sebagian dari sebuah string 
    $emailPrefix = substr($request->email, 0, 3);
    $namePrefix = substr($request->name, 0, 3);

    // Menggabungkan kedua prefix menjadi password
    $generatedPassword = $emailPrefix . $namePrefix;

    // Mengenkripsi password dengan bcrypt
    $hashedPassword = bcrypt($generatedPassword);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => $hashedPassword,
    ]);

    return redirect()->back()->with('success', 'Berhasil menambahkan Data Akun!');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|',
        ]);

        User::where('id', $id)->update([
            'name' => $request->name,
            'email'=> $request->email,
            'role'=> $request->role,
        ]);

        return redirect()->route('user.data')->with('success','Berhasil Mengubah Data User!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return redirect()->back()->with('deleted', 'Berhasil Menghapus Data!');
    }

    public function logout()
    {
        // menghapus/menghilangkan data session login
        Auth::logout();
        return redirect()->route('login');
    }
}
