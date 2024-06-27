<?php
session_start();
include_once('../config.php');

if (isset($_SESSION['token'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['competencias']) && is_array($data['competencias'])) {
        $url_perfil = API_URL .  '/usuarios/candidatos/buscar';
        $competencias = array_map(function($id) {
            return intval(['id' => $id]);
        }, $data['competencias']);
        
        $payload = json_encode(['competencias' => $competencias]);
        $curl_perfil = curl_init();

        curl_setopt_array($curl_perfil, array(
            CURLOPT_URL => $url_perfil,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $_SESSION['token'],
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response_perfil = curl_exec($curl_perfil);
      
        curl_close($curl_perfil);


        if ($response_perfil !== false && ($perfil = json_decode($response_perfil, true))) {
            if (isset($perfil['candidatos']) && is_array($perfil['candidatos'])) {
                echo '<table border="1">';
                echo '<tr><th>Nome</th><th>Email</th><th>Competências</th><th>Experiência</th><th>Mensagem</th></tr>';
                foreach ($perfil['candidatos'] as $candidato) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($candidato['nome']) . '</td>';
                    echo '<td>' . htmlspecialchars($candidato['email']) . '</td>';
                    echo '<td>';
                    foreach ($candidato['competencias'] as $competencia) {
                        echo htmlspecialchars($competencia['nome']) . '<br>';
                    }
                    echo '</td>';
                    echo '<td>';
                    foreach ($candidato['experiencia'] as $experiencia) {
                        echo 'Empresa: ' . htmlspecialchars($experiencia['nome_empresa']) . '<br>';
                        echo 'Início: ' . htmlspecialchars($experiencia['inicio']) . '<br>';
                        echo 'Fim: ' . htmlspecialchars($experiencia['fim'] ?? 'Atualmente') . '<br>';
                        echo 'Cargo: ' . htmlspecialchars($experiencia['cargo']) . '<br><br>';
                    }
                    echo '</td>';
                    echo '<td>';
                    echo '<button onclick="enviarMensagem(\'' . htmlspecialchars($candidato['email']) . '\')">Enviar Mensagem</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
               
            } else {
                echo json_encode(['success' => false, 'mensagem' => 'Nenhum candidato encontrado.'. $response_perfil]);
            }
        } else {
            echo json_encode(['success' => false, 'mensagem' => 'Não foi possível obter o perfil do servidor.']);
        }
    } else {
        echo json_encode(['success' => false, 'mensagem' => 'Dados de competências inválidos.']);
    }
} else {
    echo json_encode(['success' => false, 'mensagem' => 'Erro ao enviar requisição por falta de TOKEN']);
}

?>

<script>
function enviarMensagem(email) {
    const token = '<?php echo $_SESSION['token']; ?>';

    const data = {
        candidatos: [email]
    };
    const url = '<?= API_URL ?>/mensagem';

    const xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.setRequestHeader('Authorization', 'Bearer ' + token);
    xhr.setRequestHeader('Content-Type', 'application/json');


    xhr.onload = function() {
        if (xhr.status === 200) {
            alert("Mensagem enviada com sucesso");
        } else {
            alert("Verificar Código HTTP: " + xhr.status + xhr.mensagem);
        }
    };

    xhr.onerror = function() {
        alert("Erro ao enviar mensagem.");
    };

    xhr.send(JSON.stringify(data));
}

   
</script>