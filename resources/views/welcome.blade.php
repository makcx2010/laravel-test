@extends('layouts.default')

@section('page-title', 'Главная')

@section('content')
    <h1>Пользователи</h1>

    @foreach($users as $user)
        <div class="list-group mt-3">
            <a href="/user/{{ $user->id }}">
                <button type="button" class="list-group-item list-group-item-action mb-2">
                    {{ $user->name }}
                </button>
            </a>
        </div>
    @endforeach

@endsection
