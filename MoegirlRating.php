<?php

$wgExtensionCredits[ 'parserhook' ][] = array (
  'path' => __FILE__,
  'name' => 'MoegirlRating',
  'author' => 'Fish Thirteen',
  'url' => 'https://github.com/FishThirteen/MoegirlRating',
  'description' => '自动向首页以外的，所有的条目页面底部插入星级投票。',
  'version' => 1.0,
  'license-name' => 'Apache-2.0+'
);

$wgResourceModules['ext.MoegirlRating'] = array(
    'scripts' => 'js/ext.moegirlRating.js',
	'styles' => 'css/ext.moegirlRating.css',
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'MoegirlRating'
);


$wgAPIModules[ 'MRGetTotalRating' ] = 'MRGetTotalRatingApi';
$wgAPIModules[ 'MRRate' ] = 'MRRateApi';
$wgHooks[ 'SkinAfterContent' ][] = 'MoegirlRatingHooks::onSkinAfterContent';
$wgHooks[ 'LoadExtensionSchemaUpdates' ][] = 'MoegirlRatingHooks::addDatabases';


$wgAutoloadClasses[ 'MoegirlRatingHooks' ] = __DIR__ . '/MoegirlRating.hooks.php';
$wgAutoloadClasses[ 'MRGetTotalRatingApi' ] = __DIR__ . '/MoegirlRating.MRGetTotalRatingApi.php';
$wgAutoloadClasses[ 'MRRateApi' ] = __DIR__ . '/MoegirlRating.MRRateApi.php';
$wgMoegirlRatingIncludes = __DIR__ . '/includes';
$wgAutoloadClasses[ 'MRLogging' ] = $wgMoegirlRatingIncludes . '/MRLogging.php';
$wgAutoloadClasses[ 'RatingService' ] = $wgMoegirlRatingIncludes . '/RatingService.class.php';
$wgAutoloadClasses[ 'RatingController' ] = $wgMoegirlRatingIncludes . '/RatingController.class.php';
$wgAutoloadClasses[ 'SqlSentences' ] = $wgMoegirlRatingIncludes . '/SqlSentences.class.php';

$wgMoegirlRatingLogLevel = MRLogging::$NONE;
$wgMoegirlRatingLogDir = NULL;



return true;

