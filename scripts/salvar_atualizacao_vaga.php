<?php
session_start();
include_once('../config.php');

if (!isset($_SESSION['token'])) {
    echo json_encode(['success' => false, 'mensagem' => 'Erro ao enviar requisição por falta de TOKEN']);
    exit();
}

$token = $_SESSION['token'];
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$data || !isset($data['id'])) {
        echo json_encode(['success' => false, 'mensagem' => 'Dados da vaga nao fornecidos']);
        exit();
    }

    $vaga_id = $data['id'];
    

    $url_vaga = API_URL . '/vagas/' . $vaga_id;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url_vaga,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ),
    ));

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($http_code == 201) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode($response);
    }
} else {
    echo json_encode(['success' => false, 'mensagem' => 'Nao foi possivel processar a requisicao.']);
}
