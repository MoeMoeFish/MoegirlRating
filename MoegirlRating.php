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

$wgAutoloadClasses[ 'MoegirlRatingHooks' ] = __DIR__ . '/MoegirlRating.hooks.php';
$wgHooks[ 'SkinAfterContent' ][] = 'MoegirlRatingHooks::onSkinAfterContent';

return true;

