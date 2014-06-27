<?php
header( 'Content-type: application/json' );
require_once "./RatingData.class.php";

function isAnonymous() {
  return false;
}

function insertRating() {
  $data = new RatingData();

  //For Test
  {
    $data->isSuccess = false;
    $data->errorMessage = "错误错误错误错误错误错误错误错误错误";
    $data->totalScore = 1.3;
    $data->totalUsers = 78;
    
    echo json_encode($data);
    return;
  }

}

function getWikiId() {
}

function getUserId() {
}

function getRatingId() {
  return 0;
}

function getTotalScore() {
  

  $data = new RatingData();
  $wikiId = 0;
  $userId = 0;
  $ratingId = 0;
  
  //For Test
  {
    $data->totalScore = 2.8;
    $data->totalUsers = 20;
    echo json_encode( $data );
    return;
  }


  try {
    $wikiId = getWikiId();
    $userId = 0;
    $ratingId =  getRatingId();
  } catch ( Exception $ex ) {
    $data->isSuccess = false;
    $data->errorMessage = "系统错误: 无法获取wiki信息";
    echo json_encode( $data );
    return;
  }

  try {
    $ratingService = new RatingService( $ratingId );
    $data->isAnonymous = isAnonymous();
    $data->isDuplicated = $ratingService->hasRatingToday($userId);
    $data->isSuccess;
    $ratingService->getTotalScore( wikiId, $data->totalScore, $data->totalUsers );
  } catch ( Exception $ex ) {
    echo json_encode( $data );
  }

  
  echo json_encode( $data );
}


if ($_POST[ "action" ] != null && $_POST["action"] == "rate") {
  insertRating();
} else if (isset($_GET["getscore"])) {
  getTotalScore();
} else {
  $data = new RatingData();
  $data->isSuccess = false;
  $data->errorMessage = "系统错误: 没有相应的操作";
  $data->totalScore = 3.5;
  $data->totalUsers = 101;
  echo json_encode( $data );
  return;
}


?>

