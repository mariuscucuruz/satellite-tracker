<?php

namespace App;

use Exception;
use Illuminate\Support\Facades\Http;

class N2yoClient
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
     * Various (mostly headers) values referring API usage and quota.
     *
     * @var array
     */
    private $apiLimits = [];

    /**
     * N2YO Client constructor.
     *
     * @throws Exception
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
     * @param string $operation
     * @param int $noradId
     * @return mixed
     */
    public function apiCall($operation,  int $noradId = 0)
    {
        switch ($operation)  {
            case 'details':
                $partialUrl = "/tle/$noradId/";
                break;
            case 'predictions':
                $partialUrl = "/visualpasses/$noradId/51.639785/-0.129894/0/7/1/";
                break;
            default:
                return "Operation $operation failed.";
                break;
        }

        $response = $this->apiClient->get($this->apiBaseUrl . $partialUrl . "&apiKey={$this->apiKey}");

        return $this->handleResponse($response);
    }

    /**
     * Parse API response and return JSON.
     *
     * @param $response
     * @return mixed
     */
    private function handleResponse($response)
    {
        $this->anythingToDeclare($response, "Something failed...");

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

    /**
     * @param array $headers
     * @return |null
     */
    private function updateLimits(array $headers = [])
    {
        if (!count($headers) > 0) return null;

        $this->apiLimits = $headers;
    }

}
