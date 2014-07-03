<?php
header( 'Content-type: application/json' );

require_once "./RatingController.class.php";
require_once "./RatingService.class.php";
require_once "./RatingData.class.php";

$ratingId = 1;


// The entrance
$ratingService = new RatingService();
$ratingService->setRatingId( $ratingId );

$controller = new RatingController();
$controller->setRatingService( $ratingService );

$data = null; 

echo json_encode(new RatingData());
return;


if ($_POST[ "action" ] != null && $_POST["action"] === "rate") {
  $data = $controller->rate();
} elseif (isset($_GET["getscore"])) {
  $data = $controller->getTotalScore();
} else {
  $data = $controller->actionError();
}

echo json_encode( $data );
