<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function redirect($id)
    {
        return redirect()->route('books.read', $id);
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'guest_name' => 'required|string|max:50',
            'comment' => 'required|string|min:3|max:1000',
        ]);

        $guestName = strip_tags($request->guest_name);
        $commentText = strip_tags($request->comment);

        $lastComment = Comment::where('guest_name', $guestName)
            ->latest()
            ->first();

        if ($lastComment && now()->diffInSeconds($lastComment->created_at) < 30) {
            return redirect()
                ->route('books.read', $id)
                ->with('error', 'Tunggu 30 detik sebelum komentar lagi');
        }

        Comment::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'book_id' => $id,
            'guest_name' => $guestName,
            'comment' => $commentText,
        ]);

        if (class_exists(Notification::class)) {
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'message' => 'Komentar baru dari ' . $guestName,
                    'is_read' => 0,
                ]);
            }
        }

        return redirect()
            ->route('books.read', $id)
            ->with('success', 'Komentar berhasil dikirim');
    }
}