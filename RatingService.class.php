<?php
class RatingService {

  private $ratingId;

  public function __construct( $ratingId ) {
    $this->$ratingId = $ratingId;
  }

  public function getTotalScore( $wikiId, &$totalScore, &$totalUsers ) {

  }

  public function hasRatingToday( $wikiId, $userId ) {
  }

}
?>
