<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    </head>
    <body>
    <div class="container">
        <!-- An input box and a button for tweeting -->
        <form class="form-horizontal" action="tweet-success" method="post">
            <br>
            <input type ="text" id="tweetText" name="tweetText"></input> 
            <br>
            <button class="btn btn-default" type="submit"> Tweet</button>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
        <!-- Display the history of tweets -->
        @foreach($all_tweets as $tweet)
            <h2>{{ $tweet->tweetText }}</h2>
            <p>
                {{ $tweet->timeStamp }}
            </p>    
        @endforeach
    </div>
    </body>
</html>