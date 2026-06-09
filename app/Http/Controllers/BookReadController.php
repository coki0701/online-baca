<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadHistory;
use Illuminate\Support\Facades\Auth;

class BookReadController extends Controller
{
    public function read($id)
    {
        $book = Book::with(['category', 'comments'])
            ->findOrFail($id);

        $readerKey = session()->getId();

        ReadHistory::updateOrCreate(
            [
                'visitor_id' => $readerKey,
                'book_id' => $id,
            ],
            [
                'user_id' => Auth::check() ? Auth::id() : null,
                'updated_at' => now(),
            ]
        );

        return view('books.read', compact('book'));
    }
}