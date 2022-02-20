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

    /**
     * MEMO：conditionsに設定できる条件は下記URL参照
     * https://developers.google.com/youtube/v3/docs/search/list?hl=ja
     */
    public function fetchVideos(string $part, int $limit, array $conditions = []) : array 
    {
        try {
            $count = 0;
            $resultsPageCount = 0;
            while($count < 10){
                $searchLists[$count] = $this->goolgeServiceYoutube->search->listSearch($part, $conditions);
                $resultsPageCount += $searchLists[$count]['pageInfo']['resultsPerPage'];
                if ($limit <= $resultsPageCount) {
                    break;
                }
                $conditions['pageToken'] = $searchLists[$count]["nextPageToken"];
                $count++;
            }
        } catch (Google_Service_Exception $e) {
            echo $e->getMessage();
            exit;
        } catch (Google_Exception $e) {
            echo $e->getMessage();
            exit;
        }

        foreach ($searchLists as $searchList) {
            foreach($searchList['items'] as $item)
            $videos[] = $item;
        }

        return $videos;
    }

    public function outputVideos(array $videos) :void
    {
        $videoCount = 1;
        foreach ($videos as $video) {
            $text = <<<TEXT
            {$videoCount}件目
            タイトル : {$video['snippet']['title']}
            URL : https://www.youtube.com/watch?v={$video['id']['videoId']}
            作成日時 : {$video['snippet']['publishedAt']}
            TEXT;
            echo $text . PHP_EOL . PHP_EOL;
            $videoCount++;
        }
    }
}