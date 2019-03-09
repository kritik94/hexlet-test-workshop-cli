<?php

namespace Kritik94\Weather;

use GuzzleHttp\Client;

class Weather
{
    const API_SEARCH_URL = 'https://www.metaweather.com/api/location/search/';
    const API_LOCATION_URL = 'https://www.metaweather.com/api/location/';

    private $httpClient;

    public function __construct($options = [])
    {
        $this->httpClient = $options['httpClient'] ?? new Client();
    }

    public function getInfoByCity($city)
    {
        $searchResponse = $this->httpClient->request('GET', static::API_SEARCH_URL, [
            'query' => ['query' => $city]
        ]);

        $searchJson = json_decode($searchResponse->getBody(), true);
        $locationId = $searchJson[0]['woeid'] ?? null;

        if (empty($locationId)) {
            throw new CityNotFoundException("City '$city' not found");
        }

        $weatherResponse = $this->httpClient->request(
            'get',
            static::API_LOCATION_URL . $locationId
        );

        $weatherJson = json_decode($weatherResponse->getBody(), true);
        $weatherNow = $weatherJson['consolidated_weather'][0];

        return [
            'temperature' => $weatherNow['the_temp'] ?? null,
            'airPressure' => $weatherNow['air_pressure'] ?? null,
        ];
    }
}
