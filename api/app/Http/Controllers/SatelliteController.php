<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Satellite;
use Carbon\Carbon;

class SatelliteController extends Controller
{
    /**
     * @var string
     */
    protected $apiBaseUrl;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var \Illuminate\Http\Client
     */
    protected $apiClient;

    /**
     * SatelliteController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!env('N2YO_KEY') || !env('N2YO_URL')) {
            throw new \Exception('Missing API details.');
        }

        $this->apiBaseUrl   = env('N2YO_URL');
        $this->apiKey       = env('N2YO_KEY');

        $this->apiClient    = Http::withBasicAuth('', '');
    }

    /**
     * @param Request $request
     * @param int $noradId
     * @return mixed|string
     * @throws \Exception
     */
    public function findNoradId(int $noradId)
    {
        $satellite = $this->apiCall('details', $noradId);

        if ($satellite) {
            $predictions = $this->apiCall('predictions', $noradId);

            if (!$predictions) {
                throw new \Exception("Satellite {$satellite['info']['satname']} is not visible.");
            }
        }

        return $predictions ?? $satellite ?? null;
    }

    /**
     * Route handler.
     * `/{noradId:[0-9]+}/predictions/{lat}/{lng}/{?alt:[0-9]+}/`
     *
     * @param int $noradId
     * @param float $lat
     * @param float $lng
     * @param int $alt
     * @return array
     * @throws \Exception
     */
    public function getPredictions(Request $request, int $noradId, float $lat, float $lng, int $alt = 0)
    {
        $satellite = $this->getSatellite($noradId);
        if (!$satellite) {
            throw new \Exception("Satellite #{$noradId} not found.");
        }

        // pass location with request for predictions
        $payload = [
            'latitude' => $lat ?? 51.639785,
            'longitude' => $lng ?? -0.129894,
            'altitude' => $alt ?? 0,
        ];

        $predictions = $this->apiCall('predictions', $noradId, $lat, $lng, $alt);
        if (!$predictions) {
            throw new \Exception("Satellite {$satellite['info']['satname']} is not visible.");
        }

//        echo "<ol>Satellite {$satellite['info']['satname']} will be visible {$predictions['info']['passescount']} times:";
//        foreach ($predictions['passes'] as $pass) {
//            $startTimeStamp = new Carbon($pass['startUTC']);
//            $endTimeStamp = new Carbon($pass['endUTC']);
//            echo "<li>" .
//                "from {$pass['startAzCompass']} to {$pass['endAzCompass']}, " .
//                "from {$startTimeStamp->format('H:i D, M d, Y')} until {$endTimeStamp->format('H:i D, M d, Y')}." .
//                "</li>";
//        }

        return [$satellite, $predictions];
    }

    /**
     * @todo extend this and do it properly.
     *
     * @param int $noradId
     * @return string
     */
    public function getSatellite(int $noradId)
    {
        $satellite = $this->apiCall('details', $noradId);

        // maybe cache searches (not position) for quick access?
        //$satellite = Satellite::where('noradId', $noradId)->get();

        return $satellite ?? false;
    }

    /**
     * Perform the actual API call and handle response.
     *
     * @param $operation
     * @param $payload
     * @return mixed
     */
    public function apiCall($operation, int $noradId = 0)
    {
        $url = $this->apiBaseUrl;

        $response = null;

        switch ($operation)  {
            case 'details':
                $url .= "/tle/$noradId/";
                break;
            case 'predictions':
                $url .= "/visualpasses/$noradId/51.639785/-0.129894/0/7/1/";
                break;
        }

        $response = $this->apiClient->get("$url&apiKey={$this->apiKey}");

        return $this->handleResponse($response);
    }

    /**
     * @param $response
     * @return mixed
     */
    private function handleResponse($response)
    {
        $this->anythingToDeclare($response, "Something failed...");

        //$this->doSomethingElse($response->headers());

        return $response->json();
    }

    /**
     * Warn if API response is not explicitly successful.
     *
     * @param $apiResponse
     * @param string $message
     * @return bool
     */
    private function anythingToDeclare($apiResponse, $message = null) : bool
    {
        $message = $message ?? 'Something went wrong.';

        if (isset($apiResponse['error']) || $apiResponse->status() != 200) {
            dd($message, $apiResponse['error']);
        }

        return true;
    }
}
