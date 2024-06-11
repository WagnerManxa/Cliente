<?php
include_once('../config.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['token'])) {
    echo json_encode(['success' => false, 'message' => 'Erro: falta de TOKEN.']);
    exit;
}

$input = file_get_contents('php://input');
$data_update = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Erro: dados JSON inválidos.']);
    exit;
}

$url_atualizar = API_URL . '/usuario';

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

curl_close($curl_atualizar);

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['success' => true, 'message' => 'Dados atualizados com sucesso.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar os dados: ' . $response]);
}

