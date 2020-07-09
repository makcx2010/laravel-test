<?php

namespace App;

use http\Env\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    const COMMENT_TAKE = 5;

    public static function getUserComments($id)
    {
        return Comment::where('user_id', '=', $id)->get()->take(self::COMMENT_TAKE);
    }

    public static function loadMore($id, $lastCommentId)
    {
        if (Comment::where('user_id', '=', $id)
            ->where('id', '>', $lastCommentId)->first()) {
            $restComments = Comment::where('user_id', '=', $id)->where('id', '>', $lastCommentId)->get();
            $i = 0;

            foreach ($restComments as $comment) {
                $i++;
                $author = $comment->author()->first();
                $child = $comment->parent()->first('text');

                $comments[$i] = '
                    <div class="comment-widgets">
                        <div class="d-flex flex-row comment-row m-t-0 pb-3 pr-3">
                            <div class="comment-text w-100">
                                <h4 class="font-medium">' . $author->name . '</h4>
                                <h4 class="font-medium">' . $comment->subject . '</h4>
                                <span class="m-b-15 d-block">' . $comment->text . '</span>
                                <div class="comment-footer">
                                    <span class="text-muted float-right">' . $comment->created_at . '</span>';
                if($child) {
                    $comments[$i] .= '<div class="blockquote-footer pl-3">' . $child->text . '</div>';
                } elseif($comment->has_parent_deleted) {
                    $comments[$i] .= '<div class="blockquote-footer pl-3">Сообщение было удалено</div>';
                }
                if(Auth::check()) {
                    if (Comment::ableToDelete($comment->id)) {
                        $comments[$i] .= '
                        <a href = "' . route('delete-comment', $comment->id) . '" >
                            <button type = "button" class="btn btn-danger btn-sm delete-comment" >
                                Удалить комментарий
                            </button >
                        </a >';
                    }
                    $comments[$i] .= '
                    <button type="button" class="btn btn-primary btn-sm reply"
                            data-name="' . $author->name . '" data-id="' .$comment->id . '">Ответить
                    </button>';
                }
                $comments[$i] .= '
                                </div>
                            </div>
                        </div>
                    </div>';
            }

            return response()->json([
                'comments' => $comments,
//                'existMoreComments' => self::existMore($id, $comments->max('id'))
            ], 200);
        }

        return response()->json([],404);
    }

    public static function existMore($id, $lastCommentId) {
        if (Comment::where('user_id', '=', $id)
            ->where('id', '>', $lastCommentId)->first()) {
            return true;
        }

        return false;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->hasOne(Comment::class, 'id', 'parent_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public static function ableToDelete($id)
    {
        $userId = User::getUserId();
        $comment = self::find($id);

        if ($comment->user_id == $userId || $comment->author_id == $userId) {
            return true;
        } else {
            return false;
        }
    }
}
