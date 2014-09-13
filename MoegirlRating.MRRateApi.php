<?php
class MRRateApi extends ApiBase {
	private $logger;

	public function __construct( ApiMain $mainModule, $moduleName, $modulePrefix = '' ) {
		parent::__construct( $mainModule, $moduleName, $modulePrefix );
		$this->logger = new MRLogging( __FILE__ );
	}


	public function execute() {
		$ratingId = 0;
		$user = $this->getUser();

		if (!isset( $user )) {
			$this->logger->debug( __LINE__, 'User is empty' );
			throw new Exception( 'User is empty' );
		}

		$wikiId = (int)$this->getMain()->getVal('wikiId');
		$score = $this->getMain()->getVal( 'score' );

		if (!is_int( $wikiId )) {
			$this->logger->error( __LINE__, 'wikiId 格式不正确' );
			$this->getResult()->addValue( null, $this->getModuleName(), array(
					'isSuccess' => false,
					'message' => 'wikiId 格式不正确'
					));	
			return true;
		}


		$ratingController = new RatingController( $ratingId, $wikiId, $user );

		try {
			$result = $ratingController->rate( $score );
			$this->getResult()->addValue( null, $this->getModuleName(), $result );

		} catch( Exception $ex) {
			$this->logger->error( __LINE__, 'Cannot get the rating score, wikiId %d', $wikiId );

			$this->getResult()->addValue( null, $this->getModuleName(), array(
					'isSuccess' => false,
					'message' => '服务器错误，请稍后再试'
					));

		}	

		return true;
		
	}

	public function getDescription() {
	}

	public function getAllowedParams() {
		return array_merge( parent::getAllowedParams() , array(
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
