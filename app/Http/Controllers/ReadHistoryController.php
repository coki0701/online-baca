<?php

namespace App\Http\Controllers;

use App\Models\ReadHistory;

class ReadHistoryController extends Controller
{
    public function index()
    {
        $readerKey = session()->getId();

        $histories = ReadHistory::where('visitor_id', $readerKey)
            ->with('book.category')
            ->latest()
            ->get();

        return view(
            'books.history',
            compact('histories')
        );
    }
}