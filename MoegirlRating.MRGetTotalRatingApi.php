<?php
class MRGetTotalRatingApi extends ApiBase {
	public function execute() {
		$ratingId = 0;
		$user = $this->getUser();
		$wikiId = $this->getMain()->getVal('wikiId');

		$ratingController = new RatingController( $ratingId, $wikiId, $user );

		try {
			$result = $ratingController->getTotalScore();

			$this->getResult()->addValue( null, $this->getModuleName(), $result );

		} catch (Exception $ex) {

			$this->getResult()->addValue( null, $this->getModuleName(), array(
					'isSuccess' => false,
					'message' => '服务器错误，请稍后再试'
					));
		}

		//$this->getResult()->addValue( null, $this->getModuleName(), array( 'isSuccess' => true, 'wikiId' => $user->isAnon() ) );

		//$this->getResult()->addValue( null, $this->getModuleName(), array( 
		//		'isSuccess' => true, 
		//		'isDuplicated' => false, 
		//		'isAnonymous' => false, 
		//		'totalScore' => 3.7, 
		//		'totalUsers' => 98 ));


		return true;
	}

	public function getDescription() {
	}

	public function getAllowedParams() {
		return array_merge( parent::getAllowedParams(), array(
			'wikiId' => array (
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true
			)
		) );
	}

	public function getParamDescription() {
	}

	public function getExample() {
	}
}

