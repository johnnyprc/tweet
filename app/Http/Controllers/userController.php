<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request as Request;

class UserController extends Controller
{
    function index()
    {

        return view('users.tweet');
        // return View::make('../../welcome');
    }

    public function tweet(Request $request)
    {
        $tweetText = $request->input('tweetText');
        $connection = new TwitterOAuth(getenv('TWITTER_CLIENT_ID'),
                                       getenv('TWITTER_CLIENT_SECRET'),
                                       getenv('TWITTER_ACCESS_TOKEN'),
                                       getenv('TWITTER_ACCESS_TOKEN_SECRET'));  
        $connection->host = 'https://api.twitter.com/1.1/'; 
        $statues = $connection->post("statuses/update", ["status" => $tweetText]); 
        echo "data: " . $tweetText;
        return view('welcome');
    }
}