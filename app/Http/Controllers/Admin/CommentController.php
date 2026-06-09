<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Notification;

class CommentController extends Controller
{
    public function index()
    {
        Notification::where('is_read', 0)
            ->where('message', 'like', '%Komentar baru%')
            ->update([
                'is_read' => 1
            ]);

        $comments = Comment::with('user', 'book')
            ->latest()
            ->get();

        return view(
            'admin.comments.index',
            compact('comments')
        );
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return back()->with(
                'error',
                'Komentar tidak ditemukan'
            );
        }

        Notification::where(
            'message',
            'like',
            '%' . $comment->guest_name . '%'
        )->delete();

        $comment->delete();

        return back()->with(
            'success',
            'Komentar berhasil dihapus'
        );
    }
}