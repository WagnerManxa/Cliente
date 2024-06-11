<?php
include_once('..\config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipoCadastro = $_POST['tipoCadastro'];
    
    if ($tipoCadastro === 'empresa') {
        $data = array(
            'ramo' => $_POST['ramo'],
            'descricao' => $_POST['descricao'],
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'senha' => md5($_POST['senha']),
        );
        $url = API_URL.'/usuarios/empresa';
    } else {
        $data = array(
            'nome' => $_POST['nome'],
            'email' => $_POST['email'],
            'senha' => md5($_POST['senha']),
        );
        $url = API_URL.'/usuarios/candidatos';
    }

    list($response, $httpCode) = makeCurlRequest($url, $data, ['Content-Type: application/json'], 'POST');
    
    echo "Mensagem enviada para o servidor: \n";
    echo nl2br(json_encode($data, JSON_PRETTY_PRINT) . "\n\n");
    echo "Mensagem recebida do servidor: \n";
    echo nl2br($response . ", ". $httpCode."\n\n");
    $_SESSION['retorno'] = ("Respostado Servidor: " .$response . ", Code: ". $httpCode);
} else {
    
    header('Location: ../index.php');

    exit();
}
