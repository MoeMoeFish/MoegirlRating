=功能
萌娘百科( http://zh.moegirl.org ) 的评分功能，在每篇 wiki 的下方加入一个 五星 的打分功能。

每个用户对任意 wiki 每日进行一次打分

每篇 wiki 显示的 平均分和总打分人数是 30 日内的数据

=安装

将此工程 clone 到mediawiki 的extension 目录中
```shell
cd mediawiki/extension
git clone https://github.com/FishThirteen/MoegirlRating.git
```

在 `mediawiki/LocalSettings.php` 中加入 `require_once "$IP/extensions/MoegirlRating/MoegirlRating.php";`

升级数据库, 具体原理参考 https://www.mediawiki.org/wiki/Manual:Update.php 
```
cd mediawiki/maintenance
php update.php
```

一切 OK, 就应该在 wiki 中看到打分控件了

=参数设置
