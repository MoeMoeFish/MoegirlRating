<?php
header( 'Content-type: application/json' );

require_once "./RatingController.class.php";
require_once "./RatingService.class.php";

$ratingId = 0;


// The entrance
$ratingService = new RatingService();
$ratingService->setRatingId( $ratingId );



$controller = new RatingController();
$controller->setRatingService( $ratingService );

//echo json_encode( array( isSuccess => true, totalScore => 2.7, totalUsers => 11  ) );
//return;


if ($_POST[ "action" ] != null && $_POST["action"] === "rate") {
  $data = $controller->rate();
} elseif (isset($_GET["getscore"])) {
  $data = $controller->getTotalScore();
} else {
  $data = $controller->actionError();
}

echo json_encode( $data );
