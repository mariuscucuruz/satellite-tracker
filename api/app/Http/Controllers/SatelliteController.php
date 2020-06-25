<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\N2yoClient;

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
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->apiClient = new N2yoClient();
    }

    /**
     * Route Handler for `/{noradId:[0-9]+}`.
     *
     * @param int $noradId
     * @return \Illuminate\Http\JsonResponse|mixed|string
     * @throws \Exception
     */
    public function getSatellite(int $noradId)
    {
        if (!$satellite = $this->findByNoradId($noradId)) {
            return response("Satellite #$noradId not found.", 200);
        }

        return $satellite;
    }

    /**
     * Route handler.
     * `/{noradId:[0-9]+}/predictions/{lat}/{lng}/{?alt}/`
     *
     * @param int $noradId
     * @param float $lat
     * @param float $lng
     * @param int $alt
     * @return array
     * @throws \Exception
     */
    public function getPredictions(int $noradId, float $lat, float $lng, int $alt = 0)
    {
        if (!SatelliteController::exists($noradId)) {
            // would return 204 but that's a no-content
            return response("Satellite #{$noradId} not found.", 201);
        }

        // pass location with request for predictions
        $payload = [
            'latitude' => $lat ?? 51.639785,
            'longitude' => $lng ?? -0.129894,
            'altitude' => $alt ?? 0,
        ];

        if (!$predictions = $this->apiClient->apiCall('predictions', $noradId)) {
            return response("Satellite {$satellite['info']['satname']} is not visible.", 201);
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

        return $predictions;
    }

    /**
     * @todo extend this and do it properly.
     *
     * @param int $noradId
     * @return string
     */
    public function findByNoradId(int $noradId)
    {
        $satellite = $this->apiClient->apiCall('details', $noradId);

        // if satellite is not named it probably doesn't exist
        if (is_null($satellite['info']['satname'])) return false;

        return $satellite;
    }

    /**
     * Static method for API call without instantiating the class.
     *
     * @param int $noradId
     * @return bool
     */
    public static function exists(int $noradId) : bool
    {
        $apiClient = new N2yoClient();

        $satellite = $apiClient->apiCall('details', $noradId);

        return !is_null($satellite['info']['satname']);
    }
}
