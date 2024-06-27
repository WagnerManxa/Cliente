<?php
session_start();
include_once('../config.php');
require_once('listar_competencias_process.php');

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
?>
<form id="busca-form" method="POST" action="../scripts/buscar_perfil_process.php">
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
    
    <button class="buscarperfil-button" type="submit">Buscar</button>
</form>
