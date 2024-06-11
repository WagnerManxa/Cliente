<?php
session_start();
include_once('../config.php');
require_once('listar_competencias_process.php');
require_once('listar_ramos_process.php');

if (!isset($_SESSION['token'])) {
    echo "Erro ao enviar requisição por falta de TOKEN";
    exit();
}

$token = $_SESSION['token'];

if (isset($_SESSION['competencias'])) {
    $competencias = $_SESSION['competencias'];
} else {
    $competencias = array();
}
if (isset($_SESSION['ramos'])) {
    $ramos = $_SESSION['ramos'];
} else {
    $ramos = array();
}
?>

<div class="section">
    <h2>Cadastrar Vaga</h2>
    <form id="vaga-form" method="post" action="../scripts/cadastro_vaga_process.php">
        <div class="form-field">
            <label for="ramo_id">Selecione um Ramo:</label>
            <select id="ramo_id" name="ramo_id" required>
                <option value="">Selecione</option>
                <?php if (!empty($ramos)): ?>
                    <?php foreach ($ramos as $ramo): ?>
                        <option value="<?= htmlspecialchars($ramo['id']) ?>"><?= htmlspecialchars($ramo['nome']) ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">Nenhum ramo disponível</option>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-field">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>
        </div>
        <div class="form-field">
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" required>
        </div>
        <div class="form-field">
            <label for="competencias">Competências:</label>
            <?php if (!empty($competencias)): ?>
                <?php foreach ($competencias as $competencia): ?>
                    <div class="form-check">
                        <input type="checkbox" id="competencia<?= $competencia['id'] ?>" name="competencias[]" value="<?= $competencia['id'] ?>" data-nome="<?= htmlspecialchars($competencia['nome']) ?>">
                        <label for="competencia<?= $competencia['id'] ?>"><?= htmlspecialchars($competencia['nome']) ?></label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma competência disponível no servidor</p>
            <?php endif; ?>
        </div>
        <div class="form-field">
            <label for="experiencia">Tempo de experiência mínimo (anos):</label>
            <input type="number" id="experiencia" name="experiencia" required>
        </div>
        <div class="form-field">
            <label for="salario_min">Salário Mínimo:</label>
            <input type="number" step="0.01" id="salario_min" name="salario_min" required>
        </div>
        <div class="form-field">
            <label for="salario_max">Salário Máximo:</label>
            <input type="number" step="0.01" id="salario_max" name="salario_max">
        </div>
        <div class="form-field">
            <label for="ativo">Ativo:</label>
            <input type="checkbox" id="ativo" name="ativo" checked>
        </div>
        <button type="submit">Cadastrar</button>
        <button type="button" id="limpar-campos">Limpar Campos</button>
    </form>
</div>

<script>
    document.getElementById('limpar-campos').addEventListener('click', function() {
        document.getElementById('vaga-form').reset(); 
    });

    document.getElementById('vaga-form').addEventListener('submit', function(event) {
        event.preventDefault();

        let form = event.target;
        let formData = new FormData(form);

        let competencias = [];
        form.querySelectorAll('input[name="competencias[]"]:checked').forEach(function(checkbox) {
            competencias.push({
                id: checkbox.value,
                nome: checkbox.getAttribute('data-nome')
            });
        });

        let data = {
            ramo_id: formData.get('ramo_id'),
            titulo: formData.get('titulo'),
            descricao: formData.get('descricao'),
            competencias: competencias,
            experiencia: formData.get('experiencia'),
            salario_min: formData.get('salario_min'),
            salario_max: formData.get('salario_max') || null,
            ativo: formData.get('ativo') ? true : false
        };
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer <?= $token ?>',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Vaga cadastrada com sucesso!");
                form.reset();
            } else {
                alert("Erro ao cadastrar vaga: " + JSON.stringify(data.mensagem));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert("Erro ao cadastrar vaga.");
        });
    });
</script>
