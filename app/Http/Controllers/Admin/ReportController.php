<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ReadHistory;
use App\Models\Visitor;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();

        $totalCategories = Category::count();

        $totalBookmarks = 0;

        $totalComments = Comment::count();

        $totalHistories = ReadHistory::count();

        $popularBooks = Book::withCount('readHistories')
            ->orderByDesc('read_histories_count')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'totalBooks',
            'totalCategories',
            'totalBookmarks',
            'totalComments',
            'totalHistories',
            'popularBooks'
        ));
    }

    public function booksPdf()
    {
        $books = Book::latest()->get();

        $pdf = Pdf::loadView(
            'admin.reports.books_pdf',
            compact('books')
        );

        return $pdf->download('laporan-buku.pdf');
    }

    public function categoriesPdf()
    {
        $categories = Category::withCount('books')
            ->latest()
            ->get();

        $pdf = Pdf::loadView(
            'admin.reports.categories_pdf',
            compact('categories')
        );

        return $pdf->download('laporan-kategori.pdf');
    }

    public function visitorsPdf()
    {
        $visitors = Visitor::latest()->get();

        $pdf = Pdf::loadView(
            'admin.reports.visitors_pdf',
            compact('visitors')
        );

        return $pdf->download('laporan-pengunjung.pdf');
    }
}