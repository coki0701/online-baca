<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;

class BookCategoryController extends Controller
{
    public function show($id)
    {
        $category = Category::findOrFail($id);

        $books = Book::where('category_id', $id)
            ->with('category')
            ->latest()
            ->get();

        return view('books.category', compact(
            'category',
            'books'
        ));
    }
}