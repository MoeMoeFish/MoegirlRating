<?php
require_once "./RatingData.class.php";
require_once "./RatingService.class.php";

class RatingController {
  protected $wikiId = 0;
  protected $userId = 0;
  protected $ratingId = 0;
  protected $service;

  public function setRatingService( $ratingService ) {
    $this->service = $ratingService;
    $this->service->setRatingId( $this->getRatingId() );
  }

  public function getRatingService() {
    return $this->service;
  }

  public static function isAnonymous() {
    // ToDo
    return false;
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
    $data = new RatingData();
        
    if ( !$this->getRatingContext() ) {
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
