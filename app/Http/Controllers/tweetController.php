<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Http\Request as Request;

class TweetController extends Controller
{
    const CHAR_LIMIT = 140;                         // character limit for Twitter

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

        // separate tweets when over CHAR_LIMIT and start/finish tweet with
        //complete words by putting the cutoff word to the next tweet
        for ($index = 0; $index < strlen($tweetText); $index += $textLength ) {
            $textLength = self::CHAR_LIMIT; 
            if ($index + self::CHAR_LIMIT >= strlen($tweetText)) {
                $textLength = strlen($tweetText) - $index;
            } else {
                if (ctype_alpha($tweetText[$index + self::CHAR_LIMIT - 1]) && 
                    ctype_alpha($tweetText[$index + self::CHAR_LIMIT])) {
                    $wordPos = $this->findLatestNonLetter($tweetText, $index, $index + self::CHAR_LIMIT - 2);
                    $textLength = $wordPos - $index;
                }
            }

            $temp = substr($tweetText, $index, $textLength);
            $statues = $connection->post("statuses/update", ["status" => $temp]); 

            if ($connection->getLastHttpCode() == 200) {
                $this->insertDB($temp);
            } else {
                echo 'Invalid HTTP code from Twitter API request.';
                return view('users.error');
            }
        }

        return view('users.tweet-success');
    }

    // from the upperIndex position going backwards, find the first non-letter
    // character and return the the index of that character, if all characters
    // between lower and upper index are letters then return original upperIndex
    private function findLatestNonLetter($text, $lowerIndex, $upperIndex)
    {
        while ($upperIndex > $lowerIndex) {
            if (!ctype_alpha($text[$upperIndex])) {
                return $upperIndex;
            }
            $upperIndex--;
        }
        return $lowerIndex + self::CHAR_LIMIT;
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