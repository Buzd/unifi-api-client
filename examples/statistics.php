<?php

require __DIR__ . '/../vendor/autoload.php';
$config = require 'config.php';

use JVDS\UnifiApiClient\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;

$apiClient = new Client(new HttpClient(['base_uri' => $config['base_uri']]));

try {

    // login to the unifi controller API
    $apiClient->login($config['username'], $config['password']);

    // Fetch statistics for the given site
    $responseBody = $apiClient
        ->statistics($config['site'])
        ->getBody()
        ->getContents();

    print_r(json_decode($responseBody));

    $apiClient->logout();

} catch (RequestException $e) {
    echo $e->getMessage() . PHP_EOL;

    echo '----- Request ------' . PHP_EOL;
    echo $e->getRequest()->getBody()->getContents();
    echo PHP_EOL;

    echo '----- Response ------' . PHP_EOL;
    echo $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : '- no response -';
    echo PHP_EOL;
}