<?php 
session_start();
require_once("twitterauth/twitteroauth.php"); //Path to twitteroauth library
include("auth.php"); //auth details for twitter app

function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}
 
$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
 
$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitteruser."&count=".$notweets);
 

$encode = json_encode($tweets);

//Parse request
$response = json_decode($encode);

//Global vars
$score = 0; //Starting score
$stream = "";
$detectedWords = 0;
$allWords = 0;

$time = '';
$tweets = array();

foreach($response as $tweet) //Go through tweets
{
  $time = "{$tweet->created_at}"; //Save latest time
  $tweets[] = "{$tweet->text}"; //Store tweet text
}
/////////////////////////////////
$tweets_count = count($tweets); //Count amount of tweets in tweets array (this shouldnt be used. try $notweets)
/////////////////////////////////

$reply = "We scored the last $tweets_count tweets by @$twitteruser, ending at $time:"; //initial user interaction. tell whats happening

// Import txt
ini_set("auto_detect_line_endings", 1); //detect wordlist ending on each line
$scores = array(); //this is where data from data.txt will be stored // 

$handle = fopen("negativelist.csv","r") or die("Cant access wordlist, check filename!"); //open wordlist or fail

while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {
	$word = trim(strtolower($row[0])); // make all words in tweet lower case and trim any symbols so doesnt skip words eg. "bastard!"
	$scores[$word] = trim($row[1]); // find out what this does!!!
}

fclose($handle); //close file for safety


// $score = 0; //Starting score
// $stream = "";
// $detectedWords = 0;
// $allWords = 0;

//explode all the tweets, 
for($t=0;$t<$tweets_count;$t++) {
	$phrase = explode(" ", $tweets[$t]);

	$stream .= "<tr><td>";

    //explode
	for ($i=0,$max=count($phrase);$i<$max;$i++) {
            ++$allWords; //increade all words
		if (array_key_exists(strtolower($phrase[$i]), $scores)) {
        ++$detectedWords; //collect amount of words in array

				$this_score = $scores[$phrase[$i]];
				$score = $score+$this_score;
        $tweetAnalytics[] = $phrase[$i]; // Set up array for analytics


				// Highlight word based on score of word
				$stream .= "<span ";
            if ($this_score >= 0 && $this_score <= 2) {
                $stream .= "class=\"btn btn-default\"";

            } else if ($this_score > 2 && $this_score <= 4) {
					$stream .= "class=\"btn btn-warning\"";

				} else if ($this_score > 4 && $this_score <= 7) {
					$stream .= "class=\"btn btn-danger\"";

				} else {
					$stream .= "class=\"text-muted\""; //testing for rogue math!!!

				}
				$stream .= "><strong>".$phrase[$i]."</strong></span> ";

		} else {
			$stream .= $phrase[$i]." ";
		}
	}
	$stream .= "</td></tr>";
}
$stream .= "</tbody></table>";
$stream_head = "<table class=\"table table-hover table-condensed\"><thead><th>$reply</th></thead><tbody>";
$result_set = $stream_head.$stream;



//image switch based on score, not bothering with this as cosmetic and subject to change

   $score = intval($score);
						switch($score) { 
                  
					case $score >= 0 && $score < 5:
						$picture = "img/intro-pic.jpg";
						break;
					case $score >= 5 && $score < 10:
						$picture = "img/intro-pic.jpg";
						break;
					case $score >= 10:
						$picture = "img/intro-pic.jpg";
						break;
				}                           
?>


<?php
include('header.php');
?>

    <div class="container">

            <!-- new results box -->
                    
             <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
                    <h2 class="intro-text text-center">What did you score? <strong><?php echo $score; ?></strong>
                    </h2>
                    <hr>
                    <img class="img-responsive img-border img-left" src="<?php echo $picture; ?>" alt="">
                    <hr class="visible-xs">
                   <?php $totes2 = count($phrase); ?>
                   
                    <p>Hey! You scored <strong><?php echo $score; ?></strong></p>
                    <!-- output the results in own box -->
                    <p>In Twitbully land, we have 5 level's of bully. You ranked: </p>
                    <p>
                          <?php $score = intval($score);
                 switch($score) { 
                     
                     //expanded to rank based on negative score only
                     

                    case $score  > 0 && $score <= 5:
						echo "Level 1 : You really dont use any negative language";
						break;
                     case $score > 5 && $score <= 30:
						echo "Level 2 : You have the odd bad word.";
						break;
                      case $score > 30 && $score <= 60:
                        echo "Level 3 : You use pretty negative language"; 
                        break;
                     case $score > 60 && $score <= 100:
                        echo "Level 4 : You use really negative language";
                        break;
                        case $score >= 100: 
                        echo "Level 5 : Official Twitbully!";
                        break;
                 }

				?>                            
          </p>
                </div>
            </div>
        </div>
            

            

            
                    <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
                    <h2 class="intro-text text-center">try <strong>Twitbully</strong> again?</h2>
                    <hr>
                <!-- go back to index in own box -->
          <p>
          	<a href="index.php"><button class="col-md-6 col-md-offset-3 btn btn-info" value="Try Different Screenname">Try Again</button></a>
          </p>
                </div>
            </div>
        </div>



    	
    	<div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
            <h2 class="intro-text text-center">What were the <strong>TwitBully results? v1</strong>
                    </h2>
                    <hr>
                   
                    <hr class="visible-xs">
                    
                    <!-- php results in own box -->
                    			<?php echo $result_set; ?>
    
                </div>
            </div>
        </div>
        


        <!-- word analytics v3 -->
        	<div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
            <h2 class="intro-text text-center">What were the <strong>TwitBully analytics?</strong>
                    </h2>
                    <hr>
                   
                    <hr class="visible-xs">
                    <?php print "Amount of negative words detected: " . $detectedWords . " !" ?>
                    <?php print "Amount of words detected: " . $allWords . " !" ?>
                    <pre>
                    <?php foreach ($tweetAnalytics as $analyticDetectedWord)  echo "<pre>" . strtolower($analyticDetectedWord) . " </pre>" ;
                    ?> </pre>
                </div>
            </div>
        </div>
<!-- close container tag v3--> 


    </div> 
<!-- close final container tag -->
    
    
<?php
include('footer.php');
?>