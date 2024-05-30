<?php
if (isset($_SESSION['token'])) {
    $url_competencias = API_URL.'/competencias'; 

    $curl_competencias = curl_init();

    curl_setopt_array($curl_competencias, array(
        CURLOPT_URL => $url_competencias,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $_SESSION['token'],
            'Content-Type: application/json',
        )
    ));

    $response_competencias = curl_exec($curl_competencias);

    if ($response_competencias !== false && ($competencias = json_decode($response_competencias, true))) {
        $_SESSION['competencias'] = $competencias;
        
    } else {
        $_SESSION['competencias'] = array(); 
        $_SESSION['mensagem'] = "Não foi possível obter as competências do servidor.";
    }
} else {
    $_SESSION['mensagem'] = "Erro ao enviar requisição por falta de TOKEN";
}
