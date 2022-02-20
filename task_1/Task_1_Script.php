<?php
require_once (dirname(__FILE__) . '/../task_common/vendor/autoload.php');
require_once (dirname(__FILE__) . '/../task_common/YouTube.php');

$youtube = new YouTube();

// レスポンスの形式を指定
$part = 'snippet';

// 取得件数を指定
$limit = 100;

// 動画の検索条件を設定
$conditions = [
    'type' => 'video', //検索対象は動画
    'maxResults' => 50, // 1度の取得で最大50件
    'order' => 'date', // 作成日の新しい順
    'q' => 'SHOWROOM', //検索クエリ
    'pageToken' => null
];

$videos = $youtube->fetchVideos($part, $limit, $conditions);

$youtube->outputVideos($videos);
