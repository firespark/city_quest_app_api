<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = Comment::all();

        return view('admin.comments.index', compact(
            'comments',
        ));
    }

    public function toggle($id)
    {
        $comment = Comment::find($id);

        $comment->toggleStatus();
        return redirect()->back();
    }
}
