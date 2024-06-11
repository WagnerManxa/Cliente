<?php
include_once('../config.php');
if (isset($_SESSION['token'])) {
    $url_ramos = API_URL . '/ramos';

    $curl_ramos = curl_init();

    curl_setopt_array($curl_ramos, array(
        CURLOPT_URL => $url_ramos,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $_SESSION['token'],
            'Content-Type: application/json',
        )
    ));

    $response_ramos = curl_exec($curl_ramos);
    curl_close($curl_ramos);

    if ($response_ramos !== false && ($ramos = json_decode($response_ramos, true))) {
        $_SESSION['ramos'] = $ramos;
    } else {
        $_SESSION['ramos'] = array();
        $_SESSION['mensagem'] = "Não foi possível obter os ramos do servidor.";
    }
} else {
    $_SESSION['mensagem'] = "Erro ao enviar requisição por falta de TOKEN";
}
