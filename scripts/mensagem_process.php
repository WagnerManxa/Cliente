<?php
include_once('../config.php');
session_start();

if (isset($_SESSION['token'])) {
    $url_mensagem = API_URL . '/mensagem';

    $curl_mensagem = curl_init();

    curl_setopt_array($curl_mensagem, array(
        CURLOPT_URL => $url_mensagem,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $_SESSION['token'],
            'Content-Type: application/json',
        )
    ));

    $response_mensagem = curl_exec($curl_mensagem);
    curl_close($curl_mensagem);

    if ($response_mensagem !== false && ($mensagens = json_decode($response_mensagem, true))) {
        if (!empty($mensagens)) {
            echo '<table border="1">';
            echo '<tr><th>Empresa</th><th>Mensagem</th><th>Lida</th></tr>';

            foreach ($mensagens as $mensagem) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($mensagem['empresa'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . htmlspecialchars($mensagem['mensagem'], ENT_QUOTES, 'UTF-8') . '</td>';
                echo '<td>' . ($mensagem['lida'] ? 'Sim' : 'Não') . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo 'Nenhuma mensagem encontrada.';
        }
    } else {
        echo 'Erro ao recuperar mensagens.';
    }
} else {
    echo 'Erro ao enviar requisição por falta de TOKEN';
}

