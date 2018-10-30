<?php

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
/*
Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/index', 'PagesController@index')->name('pages.index')->middleware('verified');
Route::get('/info', 'PagesController@info')->name('pages.info');
Route::redirect('/', '/info', 301);
//load post markers
Route::post('/pages/loadposts', 'PagesController@loadposts')->name('pages.loadposts');
//load pinpoint markers
Route::post('/pages/loadpinpoints', 'PagesController@loadpinpoints')->name('pages.loadpinpoints');
//upvote manipulation
Route::post('/pages/upvote', 'PagesController@upvote')->name('pages.upvote');

Route::resource('updates', 'UpdateController');

Route::resource('posts', 'PostController')->except([
    'create', 'show', 'edit', 'update', 
]);
Route::resource('pinpoints', 'PinpointController')->except([
    'create', 'show', 'edit', 'update', 
]);
Route::resource('detectors', 'DetectorController')->except([
    'create', 'show', 'edit', 'update', 
]);

//verify is true
Auth::routes(['verify' => true]);

Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
