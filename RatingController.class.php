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

    //For Test
    {
      $data->isSuccess = true;
      $data->errorMessage = "错误错误错误错误错误错误错误错误错误";
      $data->totalScore = 1.3;
      $data->totalUsers = 78;
      
      return $data;
    }

    if ( !getRatingContext() ) {
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
      $data->isDuplicated = $service->hasRatingToday( $userId );

      if ( $data->isDuplicated ) {
        $data->isSuccess = false;
        $data->errorMessage = "重复投票: ";

        return $data;
      } 

      $service->getTotalScore( wikiId, $data->totalScore, $data->totalUsers );
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
        
    //For Test
    {
      $data->isSuccess = true;
      $data->isAnonymous = false;
      $data->totalScore = 2.8;
      $data->totalUsers = 20;

      return $data;
    }

    if ( !getRatingContext() ) {
      $data->isSuccess = false;
      $data->errorMessage = "系统错误: 无法获取wiki页面信息";

      return $data;
    }

    $data->isAnonymous = self::isAnonymous();
  
    try {
      $data->isDuplicated = $service->hasRatingToday( $userId );
      $service->getTotalScore( wikiId, $data->totalScore, $data->totalUsers );
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
      $wikiId = getWikiId();
      $userId = getUserId();
      $ratingId =  getRatingId();

      return true;
    } catch ( Exception $ex ) {
      return false;
    }
  }

  private function getWikiId() {
  }
  
  private function getUserId() {
  }
  
  private function getRatingId() {
    return 1;
  }

}
