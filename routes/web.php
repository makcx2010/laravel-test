<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'MainController@main');

Route::get('/user/{id}', 'MainController@personalPage')->name('personal-page');
Route::post('/user/{id}/add-comment', 'CommentsController@addComment')->name('add-comment');
Route::get('/user/{id}/delete-comment', 'CommentsController@deleteComment')->name('delete-comment');
Route::post('/user/{id}/load-comments/{lastCommentId}', 'CommentsController@loadMore')->name('load-more');

