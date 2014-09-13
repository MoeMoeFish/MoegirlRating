<?php
class RatingService {

  private $ratingId;
  private static $dbConnectionString = 'mysql:host=127.0.0.1;port=3306;dbname=test';
  private static $dbUser = 'moegirl';
  private static $dbPassword = '456';

  public function setRatingId( $ratingId ) {
    $this->ratingId = $ratingId;
  }

  public function getTotalScore( $wikiId, &$totalScore, &$totalUsers ) {
    try {
      $dbh = new PDO( self::$dbConnectionString, self::$dbUser, self::$dbPassword, array( PDO::ATTR_PERSISTENT => true )); 
      $stmt = $dbh->prepare( 'SELECT SUM(score) score, COUNT(score) users  FROM rating_record WHERE wiki_id = :wikiId AND rating_id = :ratingId AND DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= created_time;' );
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
    } catch (PDOException $ex) {
		MRLogging::logging( MRLogging::$FATAL, __FILE__, __LINE__, 'Database error: ' . $ex->getMessage());
		throw $ex;
    }
  }

  public function hasRatingToday( $wikiId, $userId ) {
    try {
      $dbh = new PDO( self::$dbConnectionString, self::$dbUser, self::$dbPassword, array( PDO::ATTR_PERSISTENT => true ));  
      $stmt = $dbh->prepare( 'SELECT id FROM rating_record WHERE user_id = :userId AND wiki_id = :wikiId AND rating_id = :ratingId AND DATE(created_time) = CURRENT_DATE()' );
      $stmt->bindParam( ':userId', $userId );
      $stmt->bindParam( ':wikiId', $wikiId );
      $stmt->bindParam( ':ratingId', $this->ratingId );

      $stmt->execute();

      return ( $stmt->rowCount() >= 1);

    } catch ( PDOException $ex ) {
		MRLogging::logging( MRLogging::$FATAL, __FILE__, __LINE__, 'Database error: ' . $ex->getMessage());
		throw $ex;

    }
  }

  public function rateWiki( $wikiId, $userId, $score ) {
    try {
      $dbh = new PDO( self::$dbConnectionString, self::$dbUser, self::$dbPassword, array( PDO::ATTR_PERSISTENT => true )); 
      $stmt = $dbh->prepare( 'SELECT id FROM rating_record WHERE user_id = :userId AND wiki_id = :wikiId AND rating_id = :ratingId' );
      $stmt->bindParam( ':userId', $userId );
      $stmt->bindParam( ':wikiId', $wikiId );
      $stmt->bindParam( ':ratingId', $this->ratingId );
      $stmt->execute();
      
      $stmt2 = '';
      if ( $stmt->rowCount() >= 1) {
        $stmt2 = $dbh->prepare( 'UPDATE rating_record SET score = :score, created_time = NOW() WHERE user_id = :userId AND wiki_id = :wikiId AND rating_id = :ratingId;' );
      } else {
        $stmt2 = $dbh->prepare( 'INSERT INTO rating_record(wiki_id, user_id, rating_id, created_time, score) VALUES (:wikiId, :userId, :ratingId, NOW(), :score);' ); 
      }
      $stmt2->bindParam( ':userId', $userId );
      $stmt2->bindParam( ':wikiId', $wikiId );
      $stmt2->bindParam( ':ratingId', $this->ratingId );
      $stmt2->bindParam( ':score', $score );

      $stmt2->execute();
        
    } catch ( PDOException $ex ) {
		MRLogging::logging( MRLogging::$FATAL, __FILE__, __LINE__, 'Database error: ' . $ex->getMessage());
		throw $ex;
    }
  }
}
