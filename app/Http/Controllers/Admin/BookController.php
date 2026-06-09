<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
{
    $query = Book::with('category');

    /*
    |--------------------------------------------------------------------------
    | SEARCH JUDUL / PENULIS
    |--------------------------------------------------------------------------
    */

    if ($request->search) {

        $query->where(function ($q) use ($request) {

            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('author', 'like', '%' . $request->search . '%');

        });

    }

    /*
    |--------------------------------------------------------------------------
    | FILTER KATEGORI
    |--------------------------------------------------------------------------
    */

    if ($request->category) {

        $query->where('category_id', $request->category);

    }

    $books = $query->latest()->paginate(10);

    $categories = Category::withCount('books')
        ->latest()
        ->get();

    return view('admin.books.index', compact('books', 'categories')
    );
}
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $categories = Category::all();

        return view('admin.books.create', compact('categories'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'title' => 'required|string|max:255',

            'author' => 'required|string|max:255',

            'year' => 'required|numeric|min:1900|max:' . date('Y'),

            'category_id' => 'required|exists:categories,id',

            'description' => 'required|string',

            'cover' => 'required|image|mimes:jpg,jpeg,png|max:2048',

            'file' => 'required|file|mimetypes:application/pdf|max:204800',

        ], [

            'title.required' => 'Judul buku wajib diisi',

            'author.required' => 'Penulis wajib diisi',

            'year.required' => 'Tahun wajib diisi',

            'year.min' => 'Tahun tidak valid',

            'year.max' => 'Tahun tidak boleh melebihi tahun sekarang',

            'category_id.required' => 'Kategori wajib dipilih',

            'category_id.exists' => 'Kategori tidak valid',

            'description.required' => 'Deskripsi wajib diisi',

            'cover.required' => 'Cover wajib diupload',

            'cover.image' => 'Cover harus berupa gambar',

            'cover.mimes' => 'Cover harus berformat JPG, JPEG, atau PNG',

            'cover.max' => 'Ukuran cover maksimal 2 MB',

            'file.required' => 'File PDF wajib diupload',

            'file.file' => 'File PDF tidak valid',

            'file.mimetypes' => 'File harus berformat PDF asli',

            'file.max' => 'Ukuran file PDF maksimal 200 MB',

        ]);

        /*
        |--------------------------------------------------------------------------
        | UPLOAD COVER
        |--------------------------------------------------------------------------
        */

        $cover = $request->file('cover');

        $coverOriginalName = pathinfo(
            $cover->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $coverOriginalName = str_replace(' ', '_', $coverOriginalName);

        $coverExtension = $cover->getClientOriginalExtension();

        $coverName = time() . '_' . $coverOriginalName . '.' . $coverExtension;

        $cover->storeAs('covers', $coverName, 'public');

        $coverPath = 'covers/' . $coverName;

        /*
        |--------------------------------------------------------------------------
        | UPLOAD PDF
        |--------------------------------------------------------------------------
        */

        $file = $request->file('file');

        $fileOriginalName = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );

        $fileOriginalName = str_replace(' ', '_', $fileOriginalName);

        $fileExtension = $file->getClientOriginalExtension();

        $fileName = time() . '_' . $fileOriginalName . '.' . $fileExtension;

        $file->storeAs('books', $fileName, 'public');

        $filePath = 'books/' . $fileName;

        /*
        |--------------------------------------------------------------------------
        | SAVE DATABASE
        |--------------------------------------------------------------------------
        */

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'year' => $request->year,
            'description' => $request->description,
            'cover' => $coverPath,
            'file_path' => $filePath,
            'category_id' => $request->category_id,
        ]);

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan');
    }

    /*
    |--------------------------------------------------------------------------
    | READ BOOK
    |--------------------------------------------------------------------------
    */

    public function read($id)
    {
        $book = Book::with(['category', 'comments'])
            ->findOrFail($id);

        return view('books.read', compact('book'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $book = Book::findOrFail($id);

        $categories = Category::all();

        return view('admin.books.edit', compact('book', 'categories'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([

            'title' => 'required|string|max:255',

            'author' => 'required|string|max:255',

            'year' => 'required|numeric|min:1900|max:' . date('Y'),

            'category_id' => 'required|exists:categories,id',

            'description' => 'nullable|string',

            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'file' => 'nullable|file|mimetypes:application/pdf|max:204800',

        ], 
        [

            'title.required' => 'Judul buku wajib diisi',

            'author.required' => 'Penulis wajib diisi',

            'year.required' => 'Tahun wajib diisi',

            'year.min' => 'Tahun tidak valid',

            'year.max' => 'Tahun tidak boleh melebihi tahun sekarang',

            'category_id.required' => 'Kategori wajib dipilih',

            'category_id.exists' => 'Kategori tidak valid',

            'cover.image' => 'Cover harus berupa gambar',

            'cover.mimes' => 'Cover harus berformat JPG, JPEG, atau PNG',

            'cover.max' => 'Ukuran cover maksimal 2 MB',

            'file.file' => 'File PDF tidak valid',

            'file.mimetypes' => 'File harus berformat PDF asli',

            'file.max' => 'Ukuran file PDF maksimal 200 MB',

        ]);

        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'year' => $request->year,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ];

        /*
        |--------------------------------------------------------------------------
        | UPDATE COVER
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('cover')) {

            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }

            $cover = $request->file('cover');

            $coverOriginalName = pathinfo(
                $cover->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            $coverOriginalName = str_replace(' ', '_', $coverOriginalName);

            $coverExtension = $cover->getClientOriginalExtension();

            $coverName = time() . '_' . $coverOriginalName . '.' . $coverExtension;

            $cover->storeAs('covers', $coverName, 'public');

            $data['cover'] = 'covers/' . $coverName;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE PDF
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file')) {

            if ($book->file_path) {
                Storage::disk('public')->delete($book->file_path);
            }

            $file = $request->file('file');

            $fileOriginalName = pathinfo(
                $file->getClientOriginalName(),
                PATHINFO_FILENAME
            );

            $fileOriginalName = str_replace(' ', '_', $fileOriginalName);

            $fileExtension = $file->getClientOriginalExtension();

            $fileName = time() . '_' . $fileOriginalName . '.' . $fileExtension;

            $file->storeAs('books', $fileName, 'public');

            $data['file_path'] = 'books/' . $fileName;
        }

        $book->update($data);

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Buku berhasil diupdate');
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }

        if ($book->file_path) {
            Storage::disk('public')->delete($book->file_path);
        }

        // hapus komentar buku
        \App\Models\Comment::where('book_id', $book->id)->delete();

        // hapus riwayat baca buku
        \App\Models\ReadHistory::where('book_id', $book->id)->delete();

        // hapus buku
        $book->delete();

        return back()->with('success', 'Buku berhasil dihapus');
    }
}