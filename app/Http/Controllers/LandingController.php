<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Announcement;
use App\Models\Setting;
use App\Models\Comment;
use App\Models\ReadHistory;
use App\Models\Visitor;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $todayVisitor = Visitor::where('ip_address', $request->ip())
            ->whereDate('visit_date', now()->toDateString())
            ->first();

        if (!$todayVisitor) {
            Visitor::create([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'visit_date' => now()->toDateString(),
            ]);
        }

        $query = Book::query();

        if ($request->search) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($category) use ($search) {
                        $category->where('name', 'like', '%' . $search . '%');
                    });

            });
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $books = $query->with('category')
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $categories = Category::withCount('books')
            ->latest()
            ->get();

        $announcements = Announcement::latest()
            ->take(6)
            ->get();

        $setting = Setting::first();

        $totalBooks = Book::count();

        $totalCategories = Category::count();

        $totalComments = Comment::count();

        $totalReaders = ReadHistory::count();

        $popularBooks = Book::with('category')
            ->withCount('readHistories')
            ->orderByDesc('read_histories_count')
            ->take(4)
            ->get();

        return view('landing', compact(
            'books',
            'categories',
            'announcements',
            'setting',
            'totalBooks',
            'totalCategories',
            'totalComments',
            'totalReaders',
            'popularBooks'
        ));
    }
}