<?php
class RatingService {

  private $ratingId;

  public function setRatingId( $ratingId ) {
    $this->ratingId = $ratingId;
  }

  public function getTotalScore( $wikiId, &$totalScore, &$totalUsers ) {
    try {
      $dbh = new PDO( 'mysql:host=127.0.0.1;port=3306;dbname=test', 'moegirl', '456', array( PDO::ATTR_PERSISTENT => true ));

      $stmt = $dbh->prepare( 'SELECT SUM(score) score, COUNT(score) users  FROM rating_record WHERE wiki_id = :wikiId AND rating_id = :ratingId AND DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= created_time' );
      $stmt->bindParam( ':wikiId', $wikiId);
      $stmt->bindParam( ':ratingId', $this->ratingId );

      $stmt->execute();

      if ( $stmt->rowCount() ) {
       $row = $stmt->fetch(); 
       $totalUsers = $row[ 'users' ];

       if ( $totalUsers ) {
         $totalScore = ((int)$row[ 'score' ]) / $totalUsers;
       }
       
      } else {
        $totalScore = 0;
        $totalUsers = 0;
      }
    } catch (PDOException $e) {
      //echo 'Error: ' . $e->getMessage() . "\n";
    }
  }

  public function hasRatingToday( $wikiId, $userId ) {
    try {
      $dbh = new PDO( 'mysql:host=127.0.0.1;port=3306;dbname=test', 'moegirl', '456', array( PDO::ATTR_PERSISTENT => true ));
      $stmt = $dbh->prepare( 'SELECT id FROM rating_record WHERE user_id = :userId AND wiki_id = :wikiId AND rating_id = :ratingId AND DATE(created_time) = CURRENT_DATE()' );
      $stmt->bindParam( ':userId', $userId );
      $stmt->bindParam( ':wikiId', $wikiId );
      $stmt->bindParam( ':ratingId', $this->ratingId );

      $stmt->execute();

      return ( $stmt->rowCount() >= 1);

    } catch ( PDOException $ex ) {
      echo 'Error: ' . $e->getMessage() . "\n";

    }
  }


}
