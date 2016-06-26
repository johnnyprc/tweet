<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request as Request;

class TweetController extends Controller
{
    function index()
    {
        $tweets = \App\Tweet::all();
        return view('users.home', [ 'all_tweets' => $tweets ]);
    }

    public function tweet(Request $request)
    {
        // create authetication connection and update status on twitter, if 
        // request is succesfull insert tweet into DB, report error otherwise
        $tweetText = $request->input('tweetText');
        $connection = new TwitterOAuth(getenv('TWITTER_CLIENT_ID'),
                                       getenv('TWITTER_CLIENT_SECRET'),
                                       getenv('TWITTER_ACCESS_TOKEN'),
                                       getenv('TWITTER_ACCESS_TOKEN_SECRET'));  
        $connection->host = 'https://api.twitter.com/1.1/'; 
        $statues = $connection->post("statuses/update", ["status" => $tweetText]); 

        if ($connection->getLastHttpCode() == 200) {
            $this->insertDB($tweetText);
        } else {
            echo 'Invalid HTTP code from Twitter API request.';
            return view('users.error');
        }

        return view('users.tweet-success');
    }

    private function insertDB($text)
    {
        //insert tweet into database with the current time formated to MySQL datetime type
        //if there are any errors the user will be redirected to an error page
        date_default_timezone_set('America/Los_Angeles');
        $tweetTime = date("Y-m-d H:i:s");
        try {
            \DB::table('tweets')->insert(['tweetText' => $text, 'timeStamp' => $tweetTime]);
        }catch(\Exception $e) {
            echo 'Error when inserting into database: ',  $e->getMessage(), "\n";
            return view('users.error');
        }
    }
}