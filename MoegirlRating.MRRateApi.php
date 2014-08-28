<?php
class MRRateApi extends ApiBase {
	public function execute() {
		$ratingId = 0;
		$user = $this->getUser();

		if (!isset( $user )) {
			MRLogging::logging(MRLogging::$DEBUG, __FILE__, __LINE__, "Can't get user");
			throw new Exception( 'Can\'t get user' );

		}

		$wikiId = $this->getMain()->getVal('wikiId');

		$ratingController = new RatingController( $ratingId, $wikiId, $user );

	}

	public function getDescription() {
	}

	public function getAllowedParams() {
		new array_merge( parent::getAllowedParams() , array(
				'wikiId' => array(
					ApiBase::PARAM_TYPE => 'int',
					ApiBase::PARAM_REQUIRED => true
				),
				'score' => array(
					ApiBase::PARAM_TYPE => 'int',
					ApiBase::PARAM_REQUIRED => true)
				));
	}

	public function getExample() {
	}

}
