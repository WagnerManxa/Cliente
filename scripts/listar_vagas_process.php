<?php
session_start();

include_once('../config.php');
require_once('listar_ramos_process.php');
require_once('listar_competencias_process.php');

if (isset($_SESSION['token'])) {
    $url_vagas = API_URL . '/vagas';

    $curl_vagas = curl_init();

    curl_setopt_array($curl_vagas, array(
        CURLOPT_URL => $url_vagas,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $_SESSION['token'],
            'Content-Type: application/json',
        )
    ));

    $response_vagas = curl_exec($curl_vagas);
    curl_close($curl_vagas);

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

    if ($response_vagas !== false && ($vagas = json_decode($response_vagas, true))) {
?>
        <div class="section">
            <h2>Listar Vagas</h2>
            <?php foreach ($vagas as $vaga): ?>
                <div class="vaga-card" >
                    <table border="1" cellpadding="5" cellspacing="0" >
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
                                <?php 
                                $competencias = [];
                                foreach ($vaga['competencias'] as $competencia) {
                                    $competencias[] = htmlspecialchars($competencia['id']);
                                    echo htmlspecialchars($competencia['nome'] . "; ") ?? 'N/A';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                    <button class="deletar-button" data-id="<?= $vaga['id'] ?>">Excluir</button>
                    <button class="atualizar-button" data-id="<?= $vaga['id'] ?>">Atualizar</button>
                    <button class="buscarperfil-button" data-competencias='<?= json_encode($competencias) ?>'>Buscar Perfil</button>
                </div>
                <br>    
            <?php endforeach; ?>
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
        echo("Não foi possível obter as vagas do servidor.  ");
        var_dump($response_vagas);
    }
} else {
    echo("Erro ao enviar requisição por falta de TOKEN");
}
?>
