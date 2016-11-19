<?php

namespace App\Entities;

use Illuminate\Support\Facades\Log;

class FlickrEntity
{
    private $baseUrl = 'https://api.flickr.com/services/rest/';

    private $apiKey;

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
    public function generateUrl($params)
    {
        $url = "{$this->baseUrl}?";


        foreach ($params as $key => $value) {
            $urlParts[] = "{$key}={$value}";
        }
        $urlParts[] = 'nojsoncallback=1';
        $urlParts[] = 'format=json';
        $urlParts[] = "api_key=" . $this->apiKey;
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
        $response = $this->sendRequestToFlickr($this->generateUrl([
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
        $response = $this->sendRequestToFlickr($this->generateUrl([
            'method' => $method,
            'photo_id' => $photo_id
        ]));
        return $response;
    }
}