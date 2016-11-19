<?php

namespace App\Entities;

use Illuminate\Support\Facades\Log;

class FlickrEntity
{
    private $baseUrl = 'https://api.flickr.com/services/rest/';

    protected $apiKey;

    /**
     * FlickrEntity constructor.
     */
    public function __construct()
    {
        $this->apiKey = env('API_KEY');
    }

    /**
     * @param $str
     * @return string
     */
    public function generateUti($str)
    {
        $url = "{$this->baseUrl}?";

        $str = array_merge($str,[
            'nojsoncallback' => '1',
            'format' => 'json'
        ]);
        foreach ($str as $key => $value) {
            $urlParts[] = "{$key}={$value}";
        }

        $urlParts[] = "api_key=".$this->apiKey;
        return $url . implode('&', $urlParts);
    }

    /**
     * @param $url
     * @return mixed
     */
    public function sendRequestToFlickr($url)
    {
        $request = curl_init();

        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

        $result = (curl_exec($request));

        curl_close($request);
        return $result;
    }

    /**
     * @return mixed
     */
    public function getRecent()
    {
        $method = "flickr.photos.getRecent";
        $response = $this->sendRequestToFlickr($this->generateUti([
            'method' => $method
        ]));
        return $response;
    }

    /**
     * @param $photo_id
     * @return mixed
     */
    public function getSizes($photo_id)
    {
        $method = "flickr.photos.getSizes";
        $response = $this->sendRequestToFlickr($this->generateUti([
            'method' => $method,
            'photo_id' => $photo_id
        ]));
        return $response;
    }
}