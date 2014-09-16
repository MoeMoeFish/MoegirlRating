<?php

final class MoegirlRatingHooks {
	public static function onSkinAfterContent( &$data, $skin ) {
//    $data .= '<div>MoegirlRatingHooks</div>';
	$articleId = $skin->getTitle()->getArticleID();
	MRLogging::Logging( MRLogging::$DEBUG, __FILE__, __LINE__, "Append to articleId: %d", $articleId );


    $data .=<<<EOF
<div id="rating-main" style="width: 600px;">
  <div class="moegirl_rating">
    <div class="rating_title">给本篇wiki打分:</div>
    <div class="rating_body_disabled rating_main" >
      <ul>
        <li><a class="r-1" >1</a></li>
        <li><a class="r-2" >2</a></li>
        <li><a class="r-3" >3</a></li>
        <li><a class="r-4" >4</a></li>
        <li><a class="r-5" >5</a></li>
      </ul>

      <div class="rating_body_result" ></div>
    </div>
    <div class="rating_result" ><div class="result_icon loading" ></div><div class="result_text" ></div></div>
  </div>
</div>
<script type="text/javascript" >
	mw.loader.using( 'ext.MoegirlRating', function() {
		new MoegirlRatingControl( '#rating-main', $articleId ).init();
	});
</script>
EOF;

	return true;
	}

	public static function addDatabases( DatabaseUpdater $updater ) {
		$updater->addExtensionUpdate( array( 'addTable', 'rating_record', __DIR__  . '/sql/create-rating-history-table.sql', true ) );

		return true;
	}
}
