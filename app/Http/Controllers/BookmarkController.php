<?php

namespace App\Http\Controllers;

use App\Models\Book;

class BookmarkController extends Controller
{
    public function store($id)
    {
        $bookmarks = session()->get('bookmarks', []);

        if (!in_array($id, $bookmarks)) {
            $bookmarks[] = $id;
        }

        session()->put('bookmarks', $bookmarks);

        return back()->with('success', 'Buku berhasil ditambahkan ke bookmark');
    }

    public function remove($id)
    {
        $bookmarks = session()->get('bookmarks', []);

        $bookmarks = array_diff($bookmarks, [$id]);

        session()->put('bookmarks', $bookmarks);

        return back()->with('success', 'Bookmark berhasil dihapus');
    }

    public function list()
    {
        $ids = session()->get('bookmarks', []);

        $bookmarks = Book::whereIn('id', $ids)
            ->with('category')
            ->get();

        return view('books.bookmarks', compact('bookmarks'));
    }
}