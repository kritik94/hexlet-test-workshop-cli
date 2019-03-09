<?php


namespace Kritik94\Weather\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use \Kritik94\Weather\Weather;

class WeatherTest extends TestCase
{
    protected function buildHttpClient(array $bodies)
    {
        $responses = array_map(function ($body) {
            return new Response(200, [], $body);
        }, $bodies);

        return new Client(['handler' => new MockHandler($responses)]);
    }

    public function testRequestWeather()
    {
        $city = 'london';
        $httpClient = $this->buildHttpClient([
            file_get_contents(__DIR__ . '/fixtures/metaweather-search.json'),
            file_get_contents(__DIR__ . '/fixtures/metaweather-weather.json'),
        ]);

        $weatherApp = new Weather(['httpClient' => $httpClient]);
        $weatherInfo = $weatherApp->getInfoByCity($city);

        $this->assertEquals(
            [
                'temperature' => 8.2,
                'airPressure' => 1000.815,
            ],
            $weatherInfo
        );
    }
}
