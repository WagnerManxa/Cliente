<?php
session_start();
include_once('../config.php');

if (!isset($_SESSION['token'])) {
    echo "Erro ao enviar requisição por falta de TOKEN";
    exit();
}

$token = $_SESSION['token'];

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['ramo_id']) || !isset($input['titulo']) || !isset($input['descricao']) || !isset($input['competencias']) || !isset($input['experiencia']) || !isset($input['salario_min'])) {
    echo json_encode(['success' => false, 'mensagem' => 'Dados incompletos.']);
    exit();
}

$url_cadastro = API_URL . '/vagas';

$curl_cadastro = curl_init();

curl_setopt_array($curl_cadastro, array(
    CURLOPT_URL => $url_cadastro,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($input),
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
    )
));

$response_cadastro = curl_exec($curl_cadastro);
$http_code = curl_getinfo($curl_cadastro, CURLINFO_HTTP_CODE);
curl_close($curl_cadastro);

if ($http_code == 201) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'mensagem' => $response_cadastro]);
}
