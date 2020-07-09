<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{


    public function addComment($id, CommentRequest $data)
    {
        if (Auth::check()) {
            $comment = new Comment;
            $comment->user_id = $id;
            $comment->author_id = User::getUserId();
            $comment->subject = $data->subject;
            $comment->text = $data->text;

            $comment->parent_id = $data->parentId ? $data->parentId : null;

            $comment->save();

            return redirect(route('personal-page', ['id' => $id]));
        }

        return redirect('/');
    }

    public function deleteComment($id)
    {
        if (Comment::ableToDelete($id)) {
            $comment = Comment::find($id);
            $children = Comment::where('parent_id', '=', $id)->get();

            foreach ($children as $child) {
                $child->parent_id = null;
                $child->has_parent_deleted = true;
                $child->save();
            }

            $comment->delete();

            return redirect(route('personal-page', ['id' => $comment->user_id]));
        }

        return redirect('/');
    }

    public function loadMore($id, $lastCommentId) {
        return Comment::loadMore($id, $lastCommentId);
    }
}
