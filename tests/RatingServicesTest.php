<?php
ini_set( 'display_error', 'On' );
error_reporting( E_ALL );

require_once '../RatingService.class.php';
header ('Content-type: application/json');

$service = new RatingService();
$service->setRatingId( 0 );
$score = 0;
$users = 0;

$service->getTotalScore( 0, $score, $users );

echo 'Average score is ' . $score . '; Total users is ' . $users . "\n";

$isRating = $service->hasRatingToday( 1, 3 );
echo 'user 3 has rating: ' . getYesNo($isRating) . "\n";

$isRating = $service->hasRatingToday( 0, 0 );
echo 'user 0 has rating: ' . getYesNo($isRating) . "\n";

function getYesNo( $boolValue ) {
  if ( $boolValue ) {
    return 'Yes';
  } else {
    return 'No';
  }

}

//echo $score;


