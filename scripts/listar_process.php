<?php
include_once('..\config.php');

session_start();
require_once('listar_competencias_process.php');

if (isset($_SESSION['token'])) {
    $url = API_URL.'/usuario';

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $_SESSION['token'],
            'Content-Type: application/json',
        )
    ));

    $response = curl_exec($curl);

    if ($response !== false && ($usuario = json_decode($response, true))) {
        $tipo= $usuario['tipo'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados do Usuário</title>
    <link rel="stylesheet" href="../assets/css/listar_style.css" >

</head>
<body>
<div class="section">
    <h2>Dados do Usuário</h2>
    <form action="atualizar_process.php" method="post">
        <div class="group">
            <div class="form-field">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>">
            </div>
            <div class="form-field">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">
            </div>
            <div class="form-field">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha">
            </div>
        </div>
        <?php if ($tipo === 'empresa'){?>
            <div class="form-field">
                <label for="ramo">Ramo:</label>
                <input type="text" id="ramo" name="ramo" value="<?= htmlspecialchars($usuario['ramo']) ?>">
            </div>
            <div class="form-field">
                <label for="descricao">Descrição:</label>
                <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($usuario['descricao']) ?>">
            </div>
        <?php } elseif ($tipo === 'candidato'){?>
            <div class="group">
                <div class="form-field">
                    <?php 
                    if (isset($_SESSION['competencias'])) {
                        $competencias = $_SESSION['competencias'];
                    } else {
                        $competencias = array();
                    }  

                    $data = json_decode($response, true);
                    $competencias_candidato = $data['competencias'];
                    ?>
                    <label for="competencias">Competências:</label>
                    <?php if (!empty($competencias)): ?>
                        <?php foreach ($competencias as $competencia): ?>
                            <div class="form-check">
                                <input type="checkbox" id="competencia<?= $competencia['id'] ?>" name="competencias[]" value="<?= $competencia['id'] ?>" <?php if (in_array($competencia, $competencias_candidato)) echo "checked"; ?>>
                                <label for="competencia<?= $competencia['id'] ?>"><?= htmlspecialchars($competencia['nome']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nenhuma competência disponível no servidor</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="group">
                <div class="form-field">
                    <div id="experiencias-container">
                        <?php if (!empty($usuario['experiencia'])): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($usuario['experiencia'] as $experiencia): ?>
                                <div class="group">
                                    <div class="experiencia">
                                        <h3>Experiência <?= $i ?></h3>
                                        <div class="form-field">
                                            <label for="empresa<?= $i ?>">Empresa:</label>
                                            <input type="text" id="empresa<?= $i ?>" name="empresa[]" placeholder="Empresa" value="<?= htmlspecialchars($experiencia['nome_empresa']) ?>">
                                        </div>
                                        <div class="form-field">
                                            <label for="inicio<?= $i ?>">Início:</label>
                                            <input type="date" id="inicio<?= $i ?>" name="inicio[]" placeholder="Início" value="<?= htmlspecialchars($experiencia['inicio']) ?>">
                                        </div>
                                        <div class="form-field">
                                            <label for="fim<?= $i ?>">Fim:</label>
                                            <input type="date" id="fim<?= $i ?>" name="fim[]" placeholder="Fim" value="<?= $experiencia['fim'] ? htmlspecialchars($experiencia['fim']) : "Atualmente" ?>">
                                        </div>
                                        <div class="form-field">
                                            <label for="cargo<?= $i ?>">Cargo:</label>
                                            <input type="text" id="cargo<?= $i ?>" name="cargo[]" placeholder="Cargo" value="<?= htmlspecialchars($experiencia['cargo']) ?>">
                                        </div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <button type="button" id="add-experiencia">Adicionar Experiência</button>
        <?php }?>
        <button type="submit">Salvar</button>
    </form>
</div>

<script>
    document.getElementById('add-experiencia').addEventListener('click', function() {
        var container = document.getElementById('experiencias-container');
        var experienciaDiv = document.createElement('div');
        experienciaDiv.classList.add('experiencia');
        experienciaDiv.innerHTML = `
            <h3>Experiência <?= $i++ ?></h3>
            <div class="form-field">
                <label for="empresa<?= $i ?>">Empresa:</label>
                <input type="text" id="empresa<?= $i ?>" name="empresa[]" placeholder="Empresa">
            </div>
            <div class="form-field">
                <label for="inicio<?= $i ?>">Início:</label>
                <input type="text" id="inicio<?= $i ?>" name="inicio[]" placeholder="Início">
            </div>
            <div class="form-field">
                <label for="fim<?= $i ?>">Fim:
            </div>
            <div class="form-field">
                <label for="cargo<?= $i ?>">Cargo:</label>
                <input type="text" id="cargo<?= $i ?>" name="cargo[]" placeholder="Cargo">
            </div>
        `;
        container.appendChild(experienciaDiv);
    });
</script>


                

            </div>
            <div>
            <?php 
                echo "<hr>";
                echo nl2br("Mensagem enviada para o servidor: \n");
                echo nl2br("Informações da requisição: \n");
                echo nl2br('Authorization: Bearer ' . $_SESSION['token']. "<br>");
                $requestInfo = curl_getinfo($curl);
                
                foreach ($requestInfo as $key => $value) {
                    if (in_array($key, KEYS_TO_SHOW)) {
                        echo nl2br("$key: $value\n");
                    }
                }
                echo "<hr>";
                echo "Mensagem recebida do servidor: \n";
                echo nl2br($response . "\n\n");
                ?>
                </div>
        </body>
        </html>
        <?php
    } else {
        echo "Erro ao carregar os dados do usuário.";
    }
} else {
    echo "Erro ao enviar requisição por falta de TOKEN";
}   

curl_close($curl);
exit();