<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReadHistory;

class ReadHistoryController extends Controller
{
    public function index()
    {
        $histories = ReadHistory::with('user', 'book')
            ->latest()
            ->get();

        return view(
            'admin.read_histories.index',
            compact('histories')
        );
    }
}