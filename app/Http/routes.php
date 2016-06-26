<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// home page which allows user to tweet and also displays a history of tweets
Route::get('home', 'TweetController@index');

// redirected to when the tweet is sent and insert into database
Route::post('tweet-success', 'TweetController@tweet');

// redirected to when there is an error
// Route::get('error', function () {
//     return view('users.error');
// });
