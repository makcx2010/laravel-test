@extends('layouts.default')

@section('page-title', 'Профиль')

@section('content')
    <h1>Личная страница</h1>

    <div class="card" style="width: 18rem;">
        <div class="card-header">
            Имя: {{ $user->name }}
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Email: {{ $user->email }}</li>
        </ul>
    </div>

    <div class="col-lg-6 mt-3 mb-3" style="padding: 0; max-width: 600px;">
        <div class="card" id="comments-block">
            <div class="card-body text-center">
                <h4 class="card-title">Комментарии</h4>
            </div>
            @if($comments->count())
                @foreach($comments as $comment)
                    @php
                        $author = $comment->author()->first();
                        $child = $comment->parent()->first('text');
                    @endphp

                    <div class="comment-widgets">
                        <div class="d-flex flex-row comment-row m-t-0 pb-3 pr-3">
                            <div class="comment-text w-100">
                                <h4 class="font-medium">{{ $author->name }}</h4>
                                <h4 class="font-medium">{{ $comment->subject }}</h4>
                                <span class="m-b-15 d-block">{{ $comment->text }}</span>
                                <div class="comment-footer">
                                    <span class="text-muted float-right">{{ $comment->created_at }}</span>
                                    @if($child)
                                        <div class="blockquote-footer pl-3">{{ $child->text }}</div>
                                    @elseif($comment->has_parent_deleted)
                                        <div class="blockquote-footer pl-3">Сообщение было удалено</div>
                                    @endif
                                    @if($isAuth)
                                        @if(App\Comment::ableToDelete($comment->id))
                                            <a href="{{ route('delete-comment', $comment->id) }}">
                                                <button type="button" class="btn btn-danger btn-sm delete-comment">
                                                    Удалить комментарий
                                                </button>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-primary btn-sm reply"
                                                data-name="{{ $author->name }}" data-id="{{ $comment->id }}">Ответить
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($loop->index == 4 && App\Comment::existMore($user->id, $comment->id))
                        <div id="load-more-block" style="display: inline-block; margin: auto">
                            <a href="{{ route('load-more', [$user->id, $comment->id]) }}" id="load-more" class="pb-2">
                                <svg width="5em" height="5em" viewBox="0 0 16 16" class="bi bi-arrow-down-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
                                    <path fill-rule="evenodd" d="M4.646 7.646a.5.5 0 0 1 .708 0L8 10.293l2.646-2.647a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 0-.708z"></path>
                                    <path fill-rule="evenodd" d="M8 4.5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5z"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="comment-widgets">
                    <div class="d-flex flex-row comment-row m-t-0 pb-3 pr-3">
                        <div class="pl-3">
                            <p>У пользователя нет комментариев</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($isAuth)
        <form action="{{ route('add-comment', $user->id) }}" method="post">
            @csrf
            <input type="hidden" name="parentId" id="parent-id" value="">
            <div class="form-group" style="max-width: 600px;">
                <label for="exampleInputEmail1">Написать комментарий: <strong id="destination"></strong> </label>
                <input type="text" class="form-control" id="exampleInputEmail1 subject"
                       name="subject" aria-describedby="emailHelp" placeholder="Тема">
            </div>
            <div class="input-group form-group" style="max-width: 600px;">
                <textarea id="text" name="text" class="form-control custom-control" rows="3"
                          placeholder="Комметарий" style="resize:none; padding-left: 12px;"></textarea>
                <button type="submit" id="add-comment" class="input-group-addon btn btn-primary">Отправить</button>
            </div>
        </form>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @else
        <p>Оставлять комментарии могут только авторизованные пользователи.</p>
    @endif

@endsection
