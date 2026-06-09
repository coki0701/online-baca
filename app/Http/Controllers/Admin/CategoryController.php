<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{

    public function index(Request $request)
{
    $query = Category::query();

    if ($request->search) {

        $query->where(
            'name',
            'like',
            '%' . $request->search . '%'
        );

    }

    $categories = $query->latest()->paginate(10)->withQueryString();

    return view('admin.categories.index', compact('categories'));
}

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique' => 'Kategori sudah ada',
            ]);

        Category::create([
            'name' => $request->name
        ]);

        return redirect()->route('admin.categories.index')
        ->with('success','Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
    ], [
        'name.required' => 'Nama kategori wajib diisi',
        'name.unique' => 'Kategori sudah ada',
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return redirect()->route('admin.categories.index')
        ->with('success','Kategori berhasil diupdate');
    }

    public function destroy($id)
{
    $category = Category::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | CEK APAKAH MASIH DIGUNAKAN BUKU
    |--------------------------------------------------------------------------
    */

    $totalBooks = \App\Models\Book::where(
        'category_id',
        $category->id
    )->count();

    if ($totalBooks > 0) {

        return redirect()
            ->route('admin.categories.index')
            ->with(
                'error',
                'Kategori tidak bisa dihapus karena masih digunakan buku'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS KATEGORI
    |--------------------------------------------------------------------------
    */

    $category->delete();

    return redirect()
        ->route('admin.categories.index')
        ->with(
            'success',
            'Kategori berhasil dihapus'
        );
}

}