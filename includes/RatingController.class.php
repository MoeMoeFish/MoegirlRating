<?php

class RatingController {
	protected $wikiId = 0;
	protected $userId = 0;
	protected $ratingId = 0;
	protected $service;
	protected $currentUser;

	public function __construct( $ratingId, $wikiId, $currentUser ) {
		$this->ratingId = $ratingId;
		$this->currentUser = $currentUser;
		$this->userId = $currentUser->getId();
		$this->wikiId = $wikiId;
		$this->service = new RatingService();
		$this->service->setRatingId( $this->ratingId );

		MRLogging::logging( MRLogging::$TRACE, __FILE__, __LINE__, "create RatingController, ratingId: %d, userId: %d, wikiId %d",
			$this->ratingId, $this->userId, $this->wikiId );
	}

	public function isAnonymous() {
		return $this->currentUser->isAnon();
	}

	public function rate() {
		//ToDo Error, anonymous, context, duplicated, socre range,
		$data = new RatingData();

		if ( !$this->getRatingContext() ) {
			$data->isSuccess = false;
			$data->errorMessage = "系统错误: 无法获取wiki页面信息";

			return $data;
		}

		$data->isAnonymous = $this->isAnonymous();

		if ( $data->isAnonymous ) {
			$data->isSuccess = false;
			$data->errorMessage = "匿名用户不能投票";

			return $data;
		}

		try {
			$data->isDuplicated = $this->service->hasRatingToday( $this->wikiId, $this->userId );

			if ( $data->isDuplicated ) {
				$data->isSuccess = false;
				$data->errorMessage = "今日已经投票，请明日再试";

				return $data;
			} 

			$score = intval($_POST['score']);

			if ( $score == 0 || $score > 5 ) {
				return array( isSuccess => false, errorMessage => '参数错误，投票分数不正确' );
			}

			$this->service->rateWiki( $this->wikiId, $this->userId, $score);

			$this->service->getTotalScore( $this->wikiId, $data->totalScore, $data->totalUsers );
			$data->totalScore = round( $data->totalScore, 2 );
			$data->isSuccess = true;
	
			return $data;
		
		} catch ( Exception $ex ) {
			$data->isSuccess = false;
			$data->errorMessage = $ex->getMessage();
	
			return $data;
		}
	}

	public function getTotalScore() {
		$data = array();
				
		if ( !$this->checkRatingContext() ) {
			return array(
				'isSuccess' => false,
				'message' => '系统错误：无法获取wiki页面信息'
				);
		}

		$data[ 'isAnonymous' ] = $this->isAnonymous();

	
		try {
			$data[ 'isDuplicated' ] = $this->service->hasRatingToday( $this->wikiId, $this->userId );
			$totalScore = 0;
			$totalUsers = 0;
			$this->service->getTotalScore( $this->wikiId, $totalScore, $totalUsers );
			$data[ 'totalUsers' ] = $totalUsers;
			$data[ 'totalScore' ] = round( $totalScore, 2 );
			$data[ 'isSuccess' ] = true;

			MRLogging::logging( MRLogging::$DEBUG, __FILE__, __LINE__, "Rating result, wikiId: %d, totalUsers %d, totalScore %d", 
				$this->wikiId, $totalUsers, $totalScore );
				
			return $data;
		
		} catch ( Exception $ex ) {
			$MRLogging::logging( MRLogging::$DEBUG, __FILE__, __LINE__, "Get Rating total score error: %s", $ex->getMessage());
			$data[ 'isSuccess' ] = false;
			$data[ 'message' ] = $ex->getMessage();
	
			return $data;
		}
	}


	private function checkRatingContext() {
		
		if ( !isset( $this->wikiId )) {
			MRLogging::logging( MRLogging::$DEBUG, __FILE__, __LINE__, "Don't set the wikiId" );
			return false;
		}

		if ( $this->wikiId <= 0 ) {
			MRLogging::logging( MRLogging::$DEBUG, __FILE__, __LINE__, "Wiki id is less than 0" );
			return false;
		}

		if ( !isset( $this->user ))

		return true;
	}
}
