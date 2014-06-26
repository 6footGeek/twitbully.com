<?php
include('header.php');
?>

    <div class="container">

        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
                    <h2 class="intro-text text-center">What is <strong>TwitBully?</strong>
                    </h2>
                    <hr>
                    <img class="img-responsive img-border img-left" src="img/intro-pic.jpg" alt="">
                    <hr class="visible-xs">
                    <p>Twitbully looks at your last few tweets and analyses the content of them and ranks your twitter stream as a whole depending on how offensive a particular word is, and how often it appears.</p>
                   <p>At present, the algorithms are not finished but you can get a fair idea of the sentiment of the tweets in the results, as they are colour coded.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
                    <h2 class="intro-text text-center">Enter <strong>Twitter Handle</strong> to begin..
                    </h2>
                    <hr>
                      <form class="col-md-6 col-md-offset-3 form-signin" method="post" action="tweets.php">
            <p>
            <input type="text" name="screenname" class="form-control" placeholder="@johndoe" autofocus>
            </p>
            <p>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Get Tweets!</button>
            </p>
          </form>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container -->


<?php
include('footer.php');
?>