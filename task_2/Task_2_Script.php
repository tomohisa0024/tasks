<?php
require_once (dirname(__FILE__) . '/../task_common/vendor/autoload.php');
require_once (dirname(__FILE__) . '/../task_common/YouTube.php');

$youtube = new YouTube();

// レスポンスの形式を指定
$part = 'snippet';

// 取得件数を指定
$limit = 10;

// 動画の検索条件を設定
$today = strtotime(date(DATE_RFC3339));
$threeDaysAgoFromToday = date(DATE_RFC3339 , strtotime( "-3 day" , $today));

$conditions = [
    'type' => 'video', //検索対象は動画
    'maxResults' => 10, // 最大10件
    'order' => 'rating', // 評価の高い順
    'q' => 'Apex Legends',
    'publishedAfter' => $threeDaysAgoFromToday,
    'pageToken' => null
];

$videos = $youtube->fetchVideos($part, $limit, $conditions);

$i = 1;
foreach ($videos as $video) {
    $text = <<<TEXT
    $i
    タイトル : {$video['snippet']['title']}
    URL : https://www.youtube.com/watch?v={$video['id']['videoId']}
    作成日時 : {$video['snippet']['publishedAt']}
    TEXT;
    echo $text . PHP_EOL . PHP_EOL;
    $i++;
}
