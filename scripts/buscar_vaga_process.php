<?php
session_start();
include_once('../config.php');
require_once('listar_ramos_process.php');
require_once('listar_competencias_process.php');

if (!isset($_SESSION['token'])) {
    echo json_encode(['success' => false, 'mensagem' => 'Erro ao enviar requisição por falta de TOKEN']);
    exit();
}

$token = $_SESSION['token'];
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$data || !isset($data['id_busca'])) {
        echo json_encode(['success' => false, 'mensagem' => 'Dados da vagaaaa não fornecidos']);
        exit();
    }

    if (isset($_SESSION['ramos'])) {
        $ramos = $_SESSION['ramos'];
    } else {
        $ramos = array();
    }

    if (isset($_SESSION['competencias'])) {
        $competencias = $_SESSION['competencias'];
    } else {
        $competencias = array();
    }

    $vaga_id = $data['id_busca'];
    $vaga = null;
    $mensagemErro = null;

    $curl = curl_init();
    $url_vaga = API_URL . '/vagas/' . $vaga_id;
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url_vaga,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $token,
        ),
    ));

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
   

    if ($http_code == 200) {
        $vaga = json_decode($response, true);
        $status = json_decode($response)-> mensagem ?? null ;

        if (isset($vaga) && $vaga && (!$status)) {
            ?>
            <div class="vaga-card">
                <h2>Detalhes da Vaga</h2>
                <table border="1" cellpadding="5" cellspacing="0">
                    <tr>
                        <th>Id</th>
                        <td><?= htmlspecialchars($vaga['id']) ?? 'N/A' ?></td>
                    </tr>
                    <tr>
                        <th>Ramo</th>
                        <td><?= htmlspecialchars($vaga['ramo']['nome']) ?? 'N/A' ?></td>
                    </tr>
                    <tr>
                        <th>Título</th>
                        <td><?= htmlspecialchars($vaga['titulo']) ?></td>
                    </tr>
                    <tr>
                        <th>Descrição</th>
                        <td><?= htmlspecialchars($vaga['descricao']) ?></td>
                    </tr>
                    <tr>
                        <th>Experiência</th>
                        <td><?= htmlspecialchars($vaga['experiencia']) ?></td>
                    </tr>
                    <tr>
                        <th>Salário Mínimo</th>
                        <td><?= htmlspecialchars($vaga['salario_min']) ?></td>
                    </tr>
                    <tr>
                        <th>Salário Máximo</th>
                        <td><?= htmlspecialchars($vaga['salario_max']) ?></td>
                    </tr>
                    <tr>
                        <th>Ativo</th>
                        <td><?= $vaga['ativo'] ? 'Sim' : 'Não' ?></td>
                    </tr>
                    <tr>
                        <th>Competências</th>
                        <td>
                            <?php foreach ($vaga['competencias'] as $competencia): ?>
                                <?= htmlspecialchars($competencia['nome'] . "; ") ?? 'N/A' ?>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                </table>
                <button class="deletar-button" data-id="<?= $vaga['id'] ?>">Excluir</button>
                <button class="atualizar-button" data-id="<?= $vaga['id'] ?>">Atualizar</button>
            </div> 
            <script>
            document.querySelectorAll('.deletar-button').forEach(button => {
                button.addEventListener('click', function() {
                    const vagaId = this.getAttribute('data-id');
                    if (confirm('Tem certeza que deseja excluir esta vaga <?= htmlspecialchars($vaga['titulo']) ?> ?')) {
                        const token = '<?= $_SESSION['token'] ?>';
                        const url = '<?= API_URL ?>/vagas/' + vagaId;

                        const xhr = new XMLHttpRequest();
                        xhr.open('DELETE', url);
                        xhr.setRequestHeader('Authorization', 'Bearer ' + token);
                        xhr.setRequestHeader('Content-Type', 'application/json');

                        xhr.onload = function() {
                            if (xhr.status === 204) {
                                alert("Vaga deletada com sucesso");
                                window.location.reload();
                            } else {
                                alert("Verificar Código HTTP: " + xhr.status);
                            }
                        };

                        xhr.onerror = function() {
                            alert("Erro ao deletar a vaga.");
                        };

                        xhr.send();
                    }
                });
            });

            function atualizarVaga(vagaId) {
            $.ajax({
                url: '../scripts/atualizar_vaga_process.php',
                type: 'POST',
                data: { id: vagaId },
                success: function(data) {
                    $('.content').html(data);
                },
                error: function() {
                    $('.content').html('<p>Erro ao carregar a página de atualização de vaga.</p>');
                }
            });
        }

            document.querySelectorAll('.atualizar-button').forEach(button => {
                 button.addEventListener('click', function() {
                     const vagaId = this.getAttribute('data-id'); 
                     atualizarVaga(vagaId);                 
                 });
             });
        </script>
            <?php
        } else {
            echo $response;
        }
    } else {
        echo $response;
        echo (' Código HTTP: ' . $http_code);
    }
} else {
    echo json_encode(['success' => false, 'mensagem' => 'Método de requisição inválido']);
}
?>
