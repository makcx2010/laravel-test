<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function main()
    {
        return view('welcome', ['users' => User::all()]);
    }

    public function personalPage($id)
    {
        if (!($user = User::find($id))){
            return redirect('/');
        }

        $data = [
            'user' => User::find($id),
            'comments' => Comment::getUserComments($id),
            'isAuth' => Auth::check()
        ];

        return view('personal-page', $data);
    }
}
