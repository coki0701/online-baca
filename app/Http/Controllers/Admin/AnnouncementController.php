<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->get();

        return view(
            'admin.announcements.index',
            compact('announcements')
        );
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:1000',
        ], [
            'title.required' => 'Judul pengumuman wajib diisi',
            'title.max' => 'Judul maksimal 255 karakter',
            'content.required' => 'Isi pengumuman wajib diisi',
            'content.max' => 'Isi pengumuman maksimal 1000 karakter',
        ]);

        Announcement::create([
            'title' => strip_tags($request->title),
            'content' => strip_tags($request->content),
        ]);

        return redirect()
            ->route('admin.announcements')
            ->with(
                'success',
                'Pengumuman berhasil ditambahkan'
            );
    }

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();

        return back()->with(
            'success',
            'Pengumuman berhasil dihapus'
        );
    }
}