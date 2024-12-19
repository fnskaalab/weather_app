<?php

namespace App\Services;

use App\Exceptions\WeatherApiException;
use Carbon\Carbon;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    private string $apiUrl;

    private string $apiToken;

    /**
     * @throws WeatherApiException
     */
    public function __construct()
    {
        $this->apiUrl = Env::get('WEATHER_API_URL');
        $this->apiToken = Env::get('WEATHER_API_TOKEN');

        if (empty($this->apiUrl) || empty($this->apiToken)) {
            throw new WeatherApiException('Weather API URL or Token is not configured.');
        }
    }

    /**
     * @throws WeatherApiException
     */
    public function process(Carbon $date, string $location): array
    {
        $weatherData = $date->isToday()
            ? $this->getCurrentWeather($location)
            : $this->getFutureWeather($location, $date);

        return $this->formatWeatherObject($weatherData, $date);
    }

    public function getCurrentWeather(string $location): array
    {
        return Cache::remember("weather_current_{$location}", 60, function () use ($location) {
            $response = Http::get("$this->apiUrl".'current.json', [
                'key' => $this->apiToken,
                'q' => $location,
            ]);

            if ($response->failed()) {
                throw new WeatherApiException('Failed to fetch weather data: '.$response->json()['error']['message']);
            }

            return $response->json();
        });
    }

    /**
     * @throws WeatherApiException
     */
    public function getFutureWeather(string $location, Carbon $date): array
    {
        $response = Http::get("$this->apiUrl".'future.json', [
            'key' => $this->apiToken,
            'q' => $location,
            'dt' => $date->format('Y-m-d'),
        ]);

        if ($response->failed()) {
            throw new WeatherApiException('Failed to fetch weather data: '.$response->json()['error']['message']);
        }

        return $response->json();
    }

    private function formatWeatherObject(array $weatherObject, Carbon $date): array
    {
        return [
            'city' => $weatherObject['location']['name'],
            'is' => $this->getTemperature($weatherObject),
            'date' => $date->toDateString(),
        ];
    }

    private function getTemperature(array $weatherObject): float
    {
        if (array_key_exists('forecast', $weatherObject)) {
            return $weatherObject['forecast']['forecastday'][0]['day']['avgtemp_c'];
        }

        return $weatherObject['current']['temp_c'];
    }
}
