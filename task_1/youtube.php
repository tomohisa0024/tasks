<?php
require_once (dirname(__FILE__) . '/vendor/autoload.php');

class YouTube
{
    private const API_KEY = '';
    private $goolgeServiceYoutube;

    public function __construct()
    {
        $this->goolgeServiceYoutube = new Google_Service_YouTube($this->getGoogleClient());
    }

    private function getGoogleClient()
    {
        $googleClient = new Google_Client();
        $googleClient->setDeveloperKey(self::API_KEY);
        return $googleClient;
    }

    public function fetchVideos(string $part, array $options = [])
    {
        try {
            $count = 0;
            while($count < 2){
                $searchLists[$count] = $this->goolgeServiceYoutube->search->listSearch($part, $options);
                $options['pageToken'] = $searchResponse[$count]["nextPageToken"];
                $count++;
            }
        } catch (Google_Service_Exception $e) {
            echo htmlspecialchars($e->getMessage());
            exit;
        } catch (Google_Exception $e) {
            echo htmlspecialchars($e->getMessage());
            exit;
        }

        foreach ($searchLists as $searchList) {
            foreach($searchList['items'] as $item)
            $videos[] = $item;
        }
        return $videos;
    }
}

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
