<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
        function carregarPerfil() {
            $.ajax({
                url: '../scripts/listar_process.php',
                type: 'GET',
                success: function(data) {
                    $('.content').html(data);
                },
                error: function() {
                    $('.content').html('<p>Erro ao carregar a página de perfil.</p>');
                }
            });
        }
        $('#listar-btn').click(function(e) {
            e.preventDefault();
            carregarPerfil();
        });

        //--------------------------------------------------------------
        
        function cadastrarVagas() {
            $.ajax({
                url: '../scripts/cadastrar_vagas_process.php',
                type: 'GET',
                success: function(data) {
                    $('.content').html(data);
                },
                error: function() {
                    $('.content').html('<p>Erro ao carregar a página de Cadastro de Vagas.</p>');
                }
            });
        }

        $('#cadastrarvaga-btn').click(function(e) {
            e.preventDefault();
            cadastrarVagas();
        });

        //--------------------------------------------------------------

        function listarVagas() {
            $.ajax({
                url: '../scripts/listar_vagas_process.php',
                type: 'GET',
                success: function(data) {
                    $('.content').html(data);
                },
                error: function() {
                    $('.content').html('<p>Erro ao carregar a página de listar Vagas.</p>');
                }
            });
        }

        $('#listarvagas-btn').click(function(e) {
            e.preventDefault();
            listarVagas();
        });

        //--------------------------------------------------------------

        function atualizarVaga(vagaId) {
            $.ajax({
                url: '../scripts/atualizar_vaga_process.php',
                type: 'GET',
                data: { id: vagaId },
                success: function(data) {
                    $('.content').html(data);
                },
                error: function() {
                    $('.content').html('<p>Erro ao carregar a página de atualização de vaga.</p>');
                }
            });
        }

        $(document).on('click', '.atualizar-button', function(e) {
            e.preventDefault();
            const vagaId = $(this).data('id');
            atualizarVaga(vagaId);
        });

        //--------------------------------------------------------------

        function formBuscarVaga() {
            $.ajax({
                url: '../scripts/buscar_vaga_form.php',
                type: 'GET',
                success: function(data) {
                    $('.content').html(data);
                },
                error: function() {
                    $('.vaga-encontrada').html('<p>Erro ao carregar a página de Busca de Vagas.</p>');
                }
            });
        }
        $('#formbuscarvagas-btn').click(function(e) {
            e.preventDefault();
            formBuscarVaga();
        });

        //--------------------------------------------------------------

        function buscarVaga(vagaId) {
            $.ajax({
                url: '../scripts/buscar_vaga_process.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ id_busca: vagaId }),
                success: function(data) {
                    if (data.success === false) {
                        $('.vaga-encontrada').html('<p>' + data.mensagem + '</p>');
                    } else {
                        $('.vaga-encontrada').html(data);
                    }
                },
                error: function() {
                    $('.vaga-encontrada').html('<p>Erro ao carregar a página da vaga.</p>');
                }
            });
        }

        $(document).on('submit', '#busca-form', function(e) {
            e.preventDefault();
            const vagaId = $('#id_busca').val();
            buscarVaga(vagaId);
        });

        //--------------------------------------------------------------

        function salvarVaga(vagaId) {
            $.ajax({
                url: '../scripts/salvar_atualizacao_vaga.php',
                type: 'GET',
                success: function(data) {
                    $('.content').html(data);
                },
                error: function() {
                    $('.content').html('<p>Erro ao carregar a página de atualização de vaga.</p>');
                }
            });
        }
        $(document).on('click', '.salvar-button', function(e) {
            e.preventDefault();
            salvarVaga();
        });

        

        

        

        

        

        

       

        
    });
    </script>
</head>
<body>
<div class="container">
    <div class="menu">
        <div class="profile">
            <a href="home.php"><img src="../assets/img/profile_picture.png" alt="Profile Picture"></a>
            <span><?php echo htmlspecialchars($_SESSION['email']); ?></span>
        </div>
        <ul>
            <li><a id="listar-btn" href="#">Meu Perfil</a></li>
            <li><a id="formbuscarvagas-btn" href="#">Buscar Vaga</a></li>
            <li><a id="cadastrarvaga-btn" href="#">Cadastrar Vagas</a></li>
            <li><a id="listarvagas-btn" href="#">Listar Vagas</a></li>
            <li><a href="../scripts/logout_process.php">Sair</a></li>
        </ul>
    </div>
    <div class="grupo">
        <div class="content">
            <h1>HOMEPAGE</h1>
            <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis eveniet nostrum facere, corrupti sit mollitia enim. Tempore beatae architecto, unde repellat odit nulla quos natus, fugit vero provident accusantium nihil.</p>
        </div>
        <div class="vaga-encontrada">
        </div>
    </div>
</body>
</html>
