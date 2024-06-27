<?php
session_start();
include_once('../config.php');

if (!isset($_SESSION['token'])) {
    echo "Erro ao enviar requisição por falta de TOKEN";
    exit();
}

$token = $_SESSION['token'];
$vaga_id = isset($_POST['id']) ? $_POST['id'] : null;

if ($vaga_id === null) {
    echo "ID da vaga não fornecido.";
    exit();
}

$url_vaga = API_URL . '/vagas/' . $vaga_id;

$curl_vaga = curl_init();

curl_setopt_array($curl_vaga, array(
    CURLOPT_URL => $url_vaga,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
    )
));

$response_vaga = curl_exec($curl_vaga);
$http_code = curl_getinfo($curl_vaga, CURLINFO_HTTP_CODE);
curl_close($curl_vaga);

if ($http_code !== 200) {
    echo "Erro ao obter dados da vaga.";
    exit();
}

$vaga = json_decode($response_vaga, true);

if (!$vaga) {
    echo "Vaga não encontrada.";
    exit();
}

require_once('listar_competencias_process.php');
require_once('listar_ramos_process.php');

$competencias = isset($_SESSION['competencias']) ? $_SESSION['competencias'] : [];
$ramos = isset($_SESSION['ramos']) ? $_SESSION['ramos'] : [];
?>

<div class="section">
    <h2>Atualizar Vaga</h2>
    <form id="vaga-form" method="post" action="../scripts/salvar_atualizacao_vaga.php">
        <input type="hidden" name="id" value="<?= htmlspecialchars($vaga_id) ?>">
        <div class="form-field">
            <label for="ramo_id">Selecione um Ramo:</label>
            <select id="ramo_id" name="ramo_id" required>
                <option value="">Selecione</option>
                <?php foreach ($ramos as $ramo): ?>
                    <option value="<?= htmlspecialchars($ramo['id']) ?>" <?= $ramo['id'] == $vaga['ramo']['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ramo['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-field">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($vaga['titulo']) ?>" required>
        </div>
        <div class="form-field">
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($vaga['descricao']) ?>" required>
        </div>
        <div class="form-field">
            <label for="competencias">Competências:</label>
            <?php foreach ($competencias as $competencia): ?>
                <div class="form-check">
                    <input type="checkbox" id="competencia<?= $competencia['id'] ?>" name="competencias[]" value="<?= $competencia['id'] ?>" <?= in_array($competencia['id'], array_column($vaga['competencias'], 'id')) ? 'checked' : '' ?> data-nome="<?= htmlspecialchars($competencia['nome']) ?>">
                    <label for="competencia<?= $competencia['id'] ?>"><?= htmlspecialchars($competencia['nome']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="form-field">
            <label for="experiencia">Tempo de experiência mínimo (anos):</label>
            <input type="number" id="experiencia" name="experiencia" value="<?= htmlspecialchars($vaga['experiencia']) ?>" required>
        </div>
        <div class="form-field">
            <label for="salario_min">Salário Mínimo:</label>
            <input type="number" step="0.01" id="salario_min" name="salario_min" value="<?= htmlspecialchars($vaga['salario_min']) ?>" required>
        </div>
        <div class="form-field">
            <label for="salario_max">Salário Máximo:</label>
            <input type="number" step="0.01" id="salario_max" name="salario_max" value="<?= htmlspecialchars($vaga['salario_max']) ?>">
        </div>
        <div class="form-field">
            <label for="ativo">Ativo:</label>
            <input type="checkbox" id="ativo" name="ativo" <?= $vaga['ativo'] ? 'checked' : '' ?>>
        </div>
        <button type="button" class="salvar-button">Salvar Alterações</button>
       
    </form>
</div>

<script>
   
    document.querySelector('.salvar-button').addEventListener('click', function(event) {
        event.preventDefault();

        let form = document.getElementById('vaga-form');
        let formData = new FormData(form);

        let competencias = [];
        form.querySelectorAll('input[name="competencias[]"]:checked').forEach(function(checkbox) {
            competencias.push({
                id: checkbox.value,
                nome: checkbox.getAttribute('data-nome')
            });
        });

        let dados = {
            id: formData.get('id'),
            ramo_id: formData.get('ramo_id'),
            titulo: formData.get('titulo'),
            descricao: formData.get('descricao'),
            competencias: competencias,
            experiencia: formData.get('experiencia'),
            salario_min: formData.get('salario_min'),
            salario_max: formData.get('salario_max') || null,
            ativo: formData.get('ativo') ? true : false
        };
        $.ajax({
                url: '../scripts/salvar_atualizacao_vaga.php',
                type: 'POST',
                data: JSON.stringify(dados),
                success: function(data) {
                    $('.content').html(data);
                },
                error: function() {
                    $('.content').html('<p>Erro ao carregar a página de atualização de vaga.</p>');
                }
            });
    });
</script>
