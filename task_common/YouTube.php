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
     * https://developers.google.com/youtube/v3/docs/search/list?hl=ja
     */
    public function fetchVideos(string $part, int $limit, array $options = [])
    {
        try {
            $count = 0;
            $resultsPageCount = 0;
            while($count < 10){
                $searchLists[$count] = $this->goolgeServiceYoutube->search->listSearch($part, $options);
                $resultsPageCount += $searchLists[$count]['pageInfo']['resultsPerPage'];
                if ($limit <= $resultsPageCount) {
                    break;
                }
                $options['pageToken'] = $searchLists[$count]["nextPageToken"];
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