<?php
class RatingService {

  private $ratingId;

  public function setRatingId( $ratingId ) {
    $this->$ratingId = $ratingId;
  }

  public function getTotalScore( $wikiId, &$totalScore, &$totalUsers ) {
    

  }

  public function hasRatingToday( $wikiId, $userId ) {
  }

}
