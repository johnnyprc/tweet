<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Auth;
use Socialite;
use Validator;
use App\Http\Controllers\Controller;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get request token from Twitter and then redirect the user to the Twitter
     * authorization page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        $connection = new TwitterOAuth(getenv('TWITTER_CLIENT_ID'),
                                       getenv('TWITTER_CLIENT_SECRET'));
        $request_token  = $connection->oauth('oauth/request_token', 
                                ["oauth_callback" => getenv('CALLBACK_URL')]);

        if ($connection->getLastHttpCode() == 200) {
            $url = $connection->url('oauth/authorize', ['oauth_token' => 
                                    $request_token['oauth_token']]);
            return redirect()->away($url);
        } else {
            echo 'Invalid HTTP code from Twitter API request.';
            return view('users.error');
        }
    }

    /**
     * Obtain access token from Twitter using oauth_verifier and oauth_token
     * after the user has given authorization
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $connection = new TwitterOAuth(getenv('TWITTER_CLIENT_ID'),
                                       getenv('TWITTER_CLIENT_SECRET'));
        $params = ["oauth_verifier" => $_GET['oauth_verifier'], 
                    "oauth_token"=> $_GET['oauth_token']];
        $access_token = $connection->oauth("oauth/access_token", $params);
        
        // storing access token to database
        if ($connection->getLastHttpCode() == 200) {
            \DB::table('accesstokens')->where('id', 1)
                ->update(['oauth_token' => $access_token['oauth_token'],
                          'oauth_token_secret' => $access_token['oauth_token_secret']]);
        } else {
            echo 'Invalid HTTP code from Twitter API request.';
            return view('users.error');
        }
        return view('users.auth-success');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
