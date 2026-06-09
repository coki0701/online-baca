<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookPdfController extends Controller
{
    public function show($id)
    {
        $book = Book::findOrFail($id);

        if (!$book->file_path || !Storage::disk('public')->exists($book->file_path)) {
            abort(404);
        }

        return response()->file(
            storage_path('app/public/' . $book->file_path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($book->file_path) . '"'
            ]
        );
    }
}