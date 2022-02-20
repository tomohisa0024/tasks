<?php
require_once (dirname(__FILE__) . '/../task_common/vendor/autoload.php');
require_once (dirname(__FILE__) . '/../task_common/YouTube.php');

$youtube = new YouTube();

// レスポンスの形式を指定
$part = 'snippet';

// 取得件数を指定
$limit = 10;

$today = strtotime(date(DATE_RFC3339));
$threeDaysAgoFromToday = date(DATE_RFC3339 , strtotime( "-3 day" , $today));

// 動画の検索条件を設定
$conditions = [
    'type' => 'video', //検索対象は動画
    'maxResults' => 10, // 1度の取得で最大10件
    'order' => 'rating', // 評価の高い順
    'q' => 'Apex Legends', //検索クエリ
    'publishedAfter' => $threeDaysAgoFromToday, // 3日以内の動画を対象
    'regionCode' => 'JP', //日本での検索結果を対象
    'relevanceLanguage' => 'ja', //日本語に関連のある動画を検索対象
    'pageToken' => null
];

$videos = $youtube->fetchVideos($part, $limit, $conditions);

$youtube->outputVideos($videos);
