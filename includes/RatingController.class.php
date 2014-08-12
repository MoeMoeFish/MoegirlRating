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
		$this->wikiId = $wikiId;
		$this->service = new RatingService();
		$this->service->setRatingId( $this->ratingId );
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

		$data->isAnonymous = self::isAnonymous();

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
				
		if ( !$this->getRatingContext() ) {
			return array(
				'isSuccess' => false,
				'message' => '系统错误：无法获取wiki页面信息'
				);
			$data->isSuccess = false;
			$data->errorMessage = "系统错误: 无法获取wiki页面信息";

			return $data;
		}

		$data->isAnonymous = self::isAnonymous();
	
		try {
			$data->isDuplicated = $this->service->hasRatingToday( $this->wikiId, $this->userId );
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

	public function actionError() {
		$data = new RatingData();
		$data->isSuccess = false;
		$data->errorMessage = "系统错误: 没有相应的操作";

		return $data;
	}

	private function checkRatingContext() {
		

	}

	private function getRatingContext() {
		try {
			$this->wikiId = $this->getWikiId();
			$this->userId = $this->getUserId();
			$this->ratingId = $this->getRatingId();

			return true;
		} catch ( Exception $ex ) {
			return false;
		}
	}

	private function getWikiId() {
		return 0;
	}
	
	private function getUserId() {
		return 9;
	}
	
	private function getRatingId() {
		return 0;
	}

}
