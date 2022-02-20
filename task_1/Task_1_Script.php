<?php
require_once (dirname(__FILE__) . '/../task_common/vendor/autoload.php');
require_once (dirname(__FILE__) . '/../task_common/YouTube.php');

$youtube = new YouTube();

// レスポンスの形式を指定
$part = 'snippet';

// 動画の検索条件を設定
$conditions = [
    'type' => 'video', //検索対象は動画
    'maxResults' => 50, // 最大50件
    'order' => 'date', // 作成日の新しい順
    'q' => 'SHOWROOM',
    'pageToken' => null
];

$videos = $youtube->fetchVideos($part, $conditions);

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
