<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        //dd(auth()->user()->role);

         $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /*EDIT*/
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /*UPDATE*/
     public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:user,admin'
    ]);

    /*ADMIN TIDAK BISA MENGUBAH ROLENYA SENDIRI*/
    if ($user->id == auth()->id() && $request->role != $user->role) {
        return back()->with('error', 'Tidak bisa mengubah role akun sendiri');

    }

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role
    ]);

    return redirect()
        ->route('admin.users.index')
        ->with('success', 'Role berhasil diupdate');
}

    public function destroy($id)
    {
    $user = User::findOrFail($id);

    /*TIDAK BISA MENGHAPUS AKUN SENDIRI*/
    if ($user->id == auth()->id()) {

        return back()->with(
            'error',
            'Tidak bisa menghapus akun sendiri'
        );

    }

    /*CEK JUMLAH ADMIN*/
    $totalAdmin = User::where('role', 'admin')->count();

    //*ADMIN TERAKHIR TIDAK BISA DI HAPUS*/
    if ($user->role == 'admin' && $totalAdmin <= 1) {

        return back()->with(
            'error',
            'Admin terakhir tidak boleh dihapus'
        );

    }

    $user->delete();

    return back()->with(
        'success',
        'User berhasil dihapus'
    );

}

}