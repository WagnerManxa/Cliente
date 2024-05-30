<?php
include_once('..\config.php');
session_start();
$data = $_POST;
var_dump($data);

$input = file_get_contents('php://input');
var_dump($input);
$data_update = json_decode($input, true);
var_dump($data_update);
$url_atualizar = API_URL.'/usuario'; 

$curl_atualizar = curl_init();

curl_setopt_array($curl_atualizar, array(
    CURLOPT_URL => $url_atualizar,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'PUT', 
    CURLOPT_POSTFIELDS => json_encode($data_update), 
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $_SESSION['token'],
        'Content-Type: application/json',
    )
));

$response = curl_exec($curl_atualizar);
$httpCode = curl_getinfo($curl_atualizar, CURLINFO_HTTP_CODE);

echo "Mensagem enviada para o servidor: \n";
echo nl2br(json_encode($data_update, JSON_PRETTY_PRINT) . "\n\n");
echo "Mensagem recebida do servidor: \n";
echo nl2br($response . ", ". $httpCode."\n\n");

curl_close($curl_atualizar);
