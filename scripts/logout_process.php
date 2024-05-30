<?php
include_once('..\config.php');
session_start();

if (isset($_SESSION['token'])) {
    $url = API_URL.'/logout';

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $_SESSION['token'],
            'Content-Type: application/json'
        )
    ));

    $response = curl_exec($curl);

    if ($response !== false) {
        $responseData = json_decode($response, true);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($httpCode == 200) {
            echo $responseData['mensagem'];
            header('Location: ../index.php', true, 302);
            exit();
        } elseif ($httpCode == 401) {
            echo $responseData['mensagem'];
        } elseif ($httpCode == 500) {
            echo $responseData['mensagem'];
        } else {
            echo "Erro ao fazer logout.";
        }
    } else {
        echo "Erro ao fazer logout.";
    }

    curl_close($curl);
} else {
    header('Location: ../index.php', true, 302);
    exit();
}


