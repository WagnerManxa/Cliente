<?php
include_once('..\config.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = md5($_POST['senha']);
    $url = API_URL.'/login';
    
    $data = [
        'email' => $email,
        'senha'=> $senha,
    ];

    list($response, $httpCode) = makeCurlRequest($url, $data, ['Content-Type: application/json'], 'POST');

    if ($response !== false) {
        $responseData = json_decode($response, true);

        if ($httpCode == 200 && isset($responseData['token'])) {
            session_start();
            $_SESSION['token'] = $responseData['token'];
            $_SESSION['email'] = $email;
            
            header('Location: ../pages/home.php');
            exit();
        } else {
            echo "Mensagem enviada para o servidor: \n";
            echo nl2br(json_encode($data, JSON_PRETTY_PRINT) . "\n\n");
            echo "Mensagem recebida do servidor: \n";
            echo nl2br($responseData['mensagem']. ", ". $httpCode."\n\n" ?? 'Erro desconhecido.');
            
        }
    } else {
        echo "Erro ao fazer a requisição para o servidor.";
    }
} else {
    header('Location: ../index.php');
    exit();
}

