<?php
session_start();
include_once('../config.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$url = API_URL . '/listartokens';

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
    )
));

$response = curl_exec($curl);
curl_close($curl);


$data = json_decode($response, true);

$usuarios = isset($data['tokens']) ? $data['tokens'] : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <div class="menu">
        <div class="profile">
            <a href="home.php"><img src="../assets/img/profile_picture.png" alt="Profile Picture"></a>
            <span><?php echo htmlspecialchars($_SESSION['email']); ?></span>
        </div>
        <ul>
            <li><a href="home.php">Voltar ao Home</a></li>
        </ul>
    </div>
    <div class="grupo">
        <div class="content">
            <h1>Usuários ativos</h1>
            <?php if (!empty($usuarios)): ?>
                <table border="1" cellpadding="5" cellspacing="0">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Token</th>
                    </tr>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo isset($usuario['user_id']) ? htmlspecialchars($usuario['user_id']) : 'N/A'; ?></td>
                            <td><?php echo isset($usuario['nome']) ? htmlspecialchars($usuario['nome']) : 'N/A'; ?></td>
                            <td><?php echo isset($usuario['isEmpresa']) ? (htmlspecialchars($usuario['isEmpresa']) == 'true' ? 'Empresa' : 'Candidato') : 'N/A'; ?></td>
                            <td> <?php echo isset($usuario['token']) ? htmlspecialchars($usuario['token']) : 'N/A'; ?> </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Não há usuários ativos no momento.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
