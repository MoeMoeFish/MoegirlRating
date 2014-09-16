<?php
class RatingService {
	private $logger;	

	private $ratingId;
	private static $dbConnectionString = 'mysql:host=127.0.0.1;port=3306;dbname=mediawiki';
  //private static $dbUser = 'moegirl';
  //private static $dbPassword = '456';
	private static $dbUser = 'mediawiki';
	private static $dbPassword = '123';


	public function __construct() {
		$this->logger = new MRLogging( __FILE__ );
    }


	public function setRatingId( $ratingId ) {
		$this->ratingId = $ratingId;
	}

  public function getTotalScore( $wikiId, &$averageScore, &$totalUsers ) {
    try {
		global $wgDBprefix;
		$dbr =& wfGetDB( DB_SLAVE );
		$sql = sprintf( SqlSentences::$getAverageScoreSentence, $wgDBprefix, $wikiId, $this->ratingId );
		$this->logger->debug( 'getTotalScore sql is ' . $sql );
		
		$output = $dbr->query( $sql, __METHOD__ );
		
		$this->logger->debug( __LINE__, 'dbr result, score is ' . $output->current()->score . ' users is ' . $output->current()->users );

		if ( $output->numRows > 0 ) {
			$result = $output->current();
			$totalUsers = $result->users;
			
			$averageScore = 0;

			if ( $totalUsers ) {
				$averageScore =  $result->score / $totalUsers;
			}
			
		} else {
			$totalUsers = 0;
			$averageScore = 0;

			$this->logger->debug( __LINE__ , 'ERROR: Can\'t fetch rating scores from database.' );
		}

		$totalUsers = $output->current()->users;
		$averageScore = $output->current()->score / $totalUsers;

    } catch (MWException $ex) {
		MRLogging::logging( MRLogging::$FATAL, __FILE__, __LINE__, 'Database error: ' . $ex->getMessage());
		throw $ex;
    }
  }

  public function hasRatingToday( $wikiId, $userId ) {
    try {
		global $wgDBprefix;
		$dbr =& wfGetDB( DB_SLAVE );
		$sql = sprintf( SqlSentences::hasRatingTodaySentence, $wgDBprefix, $userId, $wikiId, $this->ratingId );
		$this->logger->debug( 'hasRatingToday sql is ', $sql );

		$output = $dbr->query( $sql, __METHOD__ );


		


      $dbh = new PDO( self::$dbConnectionString, self::$dbUser, self::$dbPassword, array( PDO::ATTR_PERSISTENT => true ));  
      $stmt = $dbh->prepare( 'SELECT id FROM mwrating_record WHERE user_id = :userId AND wiki_id = :wikiId AND rating_id = :ratingId AND DATE(created_time) = CURRENT_DATE();' );
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
      $stmt = $dbh->prepare( 'SELECT id FROM mwrating_record WHERE user_id = :userId AND wiki_id = :wikiId AND rating_id = :ratingId' );
      $stmt->bindParam( ':userId', $userId );
      $stmt->bindParam( ':wikiId', $wikiId );
      $stmt->bindParam( ':ratingId', $this->ratingId );
      $stmt->execute();
      
      $stmt2 = '';
      if ( $stmt->rowCount() >= 1) {
        $stmt2 = $dbh->prepare( 'UPDATE mwrating_record SET score = :score, created_time = NOW() WHERE user_id = :userId AND wiki_id = :wikiId AND rating_id = :ratingId;' );
      } else {
        $stmt2 = $dbh->prepare( 'INSERT INTO mwrating_record(wiki_id, user_id, rating_id, created_time, score) VALUES (:wikiId, :userId, :ratingId, NOW(), :score);' ); 
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
