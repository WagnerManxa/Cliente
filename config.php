<?php

//define('API_URL', 'http://127.0.0.1:8000');
define('API_URL', 'http://10.40.14.185:8000/api');


define('KEYS_TO_SHOW', ['url', 'content_type','scheme', 'http_code','http_version', 'primary_ip', 'primary_port', 'local_ip', 'local_port', 'effective_method']);


function makeCurlRequest($url, $data = null, $headers = [], $method = 'GET') {
    $curl = curl_init();
    
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
    ];
    
    if ($method === 'POST') {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = json_encode($data);
    }
    
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    return [$response, $httpCode];
}