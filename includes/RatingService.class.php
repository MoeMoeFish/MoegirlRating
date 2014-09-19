<?php
class RatingService {
	private $logger;	

	private $ratingId;
	private static $dbConnectionString = 'mysql:host=127.0.0.1;port=3306;dbname=mediawiki';
	private static $dbUser = 'mediawiki';
	private static $dbPassword = '123';


	public function __construct() {
		$this->logger = new MRLogging( __FILE__ );
    }


	public function setRatingId( $ratingId ) {
		$this->ratingId = $ratingId;
	}

	public function getAverageScore( $wikiId, &$averageScore, &$totalUsers ) {
		try {
			$dbr =& wfGetDB( DB_SLAVE );
			$sql = sprintf( SqlSentences::$getAverageScoreSentence, $dbr->tableName( SqlSentences::$ratingRecordTable ), $wikiId, $this->ratingId );
			$this->logger->debug( __LINE__, 'getAverageScore sql is ' . $sql );
			
			$output = $dbr->query( $sql, __METHOD__ );
			
			$this->logger->debug( __LINE__, 'database read result, score is ' . $output->current()->score . ' users is ' . $output->current()->users );
			
			if ( $output->numRows() > 0 ) {
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
		} catch ( DBQueryError $ex ) {
			$this->logger->fatal( __LINE__, 'Database error: ' . $ex->getMessage() );
			throw $ex;
		}
	}

	public function hasRatingToday( $wikiId, $userId ) {
		try {
			$dbr =& wfGetDB( DB_SLAVE );
			$sql = sprintf( SqlSentences::$hasRatingTodaySentence, $dbr->tableName( SqlSentences::$ratingRecordTable ), $userId, $wikiId, $this->ratingId );
			$this->logger->debug( __LINE__,  'hasRatingToday sql is ' . $sql );
		
			$output = $dbr->query( $sql, __METHOD__ );
		
			return ( $output->numRows() >= 1 );
		
		} catch ( DBQueryError $ex ) {
			$this->logger->fatal( __LINE__, 'Database error: ' . $ex->getMessage() );
			throw $ex;
		}
	}

	public function rateWiki( $wikiId, $userId, $score ) {
		try {
			$dbr =& wfGetDB( DB_SLAVE );
			$sql = sprintf( SqlSentences::$hasRatedWikiSentence, $dbr->tableName( SqlSentences::$ratingRecordTable ), $userId, $wikiId, $this->ratingId );
		
			$output = $dbr->query( $sql );

			$dbw =& wfGetDB( DB_MASTER );
			if ( $output->numRows() >= 1 ) {

				$sql = sprintf( SqlSentences::$rateWikiUpdateSentence, $dbw->tableName( SqlSentences::$ratingRecordTable ), $score, $userId, $wikiId, $this->ratingId );
				$this->logger->debug( __LINE__,  'rateWiki update sql: ' . $sql );
	
				$result = $dbw->query( $sql, __METHOD__ );
								
				$this->logger->debug( __LINE__, 'rate wiki update sql result: ' . $result );

			} else {
				$sql = sprintf( SqlSentences::$rateWikiInsertSentence, $dbw->tableName( SqlSentences::$ratingRecordTable ), $wikiId, $userId, $this->ratingId, $score );
				$this->logger->debug( __LINE__, 'rate wiki insert sql:' . $sql );

				$result = $dbw->query( $sql, __METHOD__ );
				$this->logger->debug( __LINE__, 'rate wiki insert sql result: ' . $result );
			}

		} catch ( DBQueryError $ex ) {
			$this->logger->fatal( __LINE__, 'Database error: ' . $ex->getMessage() );
			throw $ex;
		}
	}
}
