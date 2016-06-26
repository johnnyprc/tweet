<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    </head>
    <body>
    <div class="container">
        <br><img src="https://cdn1.iconfinder.com/data/icons/logotypes/32/twitter-128.png" alt="Twitter Icon" style="width:35px;height:35px;">
        <a href="{{ url('auth/twitter') }}">Sign in to Twitter</a>
        <!-- An input box and a button for tweeting -->
        <form class="form-horizontal" action="tweet" method="post">
            <br><textarea type ="text" id="tweetText" name="tweetText" style="font-size:12pt;height:90px;width:600px;"></textarea> 
            <br><button class="btn btn-default" type="submit" style="position:relative;left:535px">Tweet</button>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
        <!-- Display the history of tweets -->
        <h2>Tweet History</h2>
        @foreach( $all_tweets->reverse() as $tweet)
            <div class="list-group">
                <a href="#" class="list-group-item active">
                    <h4 class="list-group-item-heading">{{ $tweet->timeStamp }}</h4>
                    <p class="list-group-item-text">{{ $tweet->tweetText }}</p>
                </a>
            </div>  
        @endforeach
    </div>
    </body>
</html>