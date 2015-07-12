<?php

class RatingController {
	protected $wikiId = 0;
	protected $userId = 0;
	protected $ratingId = 0;
	protected $service;
	protected $currentUser;
	private $logger;
	private $ipAddress;

	public function __construct( $ratingId, $wikiId, $currentUser, $ipAddress ) {
		$this->ratingId = $ratingId;
		$this->currentUser = $currentUser;
		$this->wikiId = $wikiId;
		$this->service = new RatingService();
		$this->service->setRatingId( $this->ratingId );
		$this->ipAddress = $ipAddress;

		if ( $this->currentUser->isAnon() ) {
			$this->userId = $this->ipAddress;
		} else {
			$this->userId = $currentUser->getId();
		}

		
		$this->logger = new MRLogging( __FILE__ );

		$this->logger->trace( __LINE__, 'create RatingController, ratingId: %d, userId: %d, wikiId %d', $this->ratingId, $this->userId, $this->wikiId );
	}

	public function isAnonymous() {
		return $this->currentUser->isAnon();
	}

	public function rate( $score ) {
		$data = array();

		$data[ 'isAnonymous' ] = $this->isAnonymous() ? 1 : 0;

		try {

			$data[ 'isDuplicated' ] = $this->service->hasRatingToday( $this->wikiId, $this->userId ) ? 1 : 0;

			if ( $data[ 'isDuplicated' ]  == 1 ) {
				$data[ 'isSuccess' ] = 0;
				$data[ 'errorMessage' ] = '今日已经投票，请明日再试';

				return $data;
			} 

			$score = (int)$score;

			if ( $score <= 0 || $score > 5 ) {
				return array( isSuccess => 0, errorMessage => '参数错误，投票分数不正确' );
			}

			$this->service->rateWiki( $this->wikiId, $this->userId, $score);

			$averageScore = 0;
			$totalUsers = 0;

			$this->service->getAverageScore( $this->wikiId, $averageScore, $totalUsers );

			$data[ 'averageScore' ] = round( $averageScore, 2 );
			$data[ 'totalUsers' ] = (int)$totalUsers;
			$data[ 'isSuccess' ] = 1;

			return $data;
		
		} catch ( Exception $ex ) {
			return array( isSuccess => 0, errorMessage => $ex->getMessage() );
		}
	}

	public function getScore() {
		$data = array();
				
		if ( !$this->checkRatingContext() ) {
			return array(
				'isSuccess' => 0,
				'message' => '系统错误：无法获取wiki页面信息'
				);
		}

		$data[ 'isAnonymous' ] = $this->isAnonymous() ? 1 : 0;
	
		try {
			$data[ 'isDuplicated' ] = $this->service->hasRatingToday( $this->wikiId, $this->userId ) ? 1 : 0;
			$averageScore = 0;
			$totalUsers = 0;
			$this->service->getAverageScore( $this->wikiId, $averageScore, $totalUsers );
			$data[ 'totalUsers' ] = intval( $totalUsers );
			$data[ 'averageScore' ] = round( $averageScore, 2 );
			$data[ 'isSuccess' ] = 1;

			$this->logger->debug( __LINE__, 'Rating result, wikiId: %d, totalUsers %d, averageScore %d', $this->wikiId, $totalUsers, $averageScore );
				
			return $data;
		
		} catch ( Exception $ex ) {
			$this->logger->debug( __LINE__, 'Get Rating total score error: %s', $ex->getMessage() );
			$data[ 'isSuccess' ] = 0;
			$data[ 'message' ] = $ex->getMessage();
	
			return $data;
		}
	}


	private function checkRatingContext() {
		
		if ( !isset( $this->wikiId )) {
			$this->logger->debug( __LINE__, 'wiki Id is empty' );
			return false;
		}

		if ( $this->wikiId <= 0 ) {
			$this->logger->debug( __LINE__, 'Wiki id is less than 0' );
			return false;
		}

		if ( !isset( $this->user ))

		return true;
	}
}
