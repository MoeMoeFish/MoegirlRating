<?php
class MRGetTotalRatingApi extends ApiBase {
	public function execute() {
		$ratingId = 0;
		$user = $this->getUser();

		if (!isset( $user )) {
			MRLogging::logging(MRLogging::$DEBUG, __FILE__, __LINE__, "Can't the user");
			throw new Exception( 'Can\'t get user' );
		}

		$wikiId = $this->getMain()->getVal('wikiId');

		$ratingController = new RatingController( $ratingId, $wikiId, $user );

		try {
			$result = $ratingController->getTotalScore();

			$this->getResult()->addValue( null, $this->getModuleName(), $result );

		} catch (Exception $ex) {
			MRLogging::logging( MRLogging::$ERROR, __FILE__, __LINE__, 'Cannot get the rating score, wikiId %d', $wikiId);

			$this->getResult()->addValue( null, $this->getModuleName(), array(
					'isSuccess' => false,
					'message' => '服务器错误，请稍后再试'
					));
		}

		return true;
	}

	public function getDescription() {
		return 'Get the rating average score and total rating users';
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

