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

Route::get('/', function () {
    return view('book_list');
});

Route::get('/authors', function () {
    return view('author_list');
});

Route::post('/books', 'App\Http\Controllers\BookController@index');
Route::post('/books/store', 'App\Http\Controllers\BookController@store');
Route::post('/books/delete', 'App\Http\Controllers\BookController@delete');
Route::get('/books/add', 'App\Http\Controllers\BookController@create');
Route::post('/books/update', 'App\Http\Controllers\BookController@update');
Route::get('/books/{book_id}/edit', 'App\Http\Controllers\BookController@show');

Route::post('/authors', 'App\Http\Controllers\AuthorController@index');
Route::post('/authors/store', 'App\Http\Controllers\AuthorController@store');
Route::post('/authors/delete', 'App\Http\Controllers\AuthorController@delete');
Route::get('/authors/add', 'App\Http\Controllers\AuthorController@create');
Route::post('/authors/update', 'App\Http\Controllers\AuthorController@update');
Route::get('/authors/{author_id}/edit', 'App\Http\Controllers\AuthorController@show');
