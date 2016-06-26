<!DOCTYPE html>
<html>
    <head>
        <title>My Movies</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    </head>
    <body>
<!--         <div class="input-group">
            <input type="text" id="tweetText" name="tweetText"></input>
            <span class="input-group-btn">
                <button class="btn btn-default" type="button">Go!</button>
                <button class="btn btn-default" onclick="window.location='{{ url("tweet") }}'">Button</button>
            </span>
        </div> -->
    <div class="container">
        <form class="form-horizontal" action="tweet" method="post">
            <br>
            <input type ="text" id="tweetText" name="tweetText"></input> 
            <br>
            <button class="btn btn-default" type="submit"> Tweet</button>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
    </div>
    </body>
</html>