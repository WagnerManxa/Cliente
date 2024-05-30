<?php
session_start();
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

            $('ul li a[href="#"]').click(function(e) {
                e.preventDefault();
                carregarPerfil();
            });
        });
    </script>
</head>
<body>

<div class="container">
    <div class="menu">
        <div class="profile">
            <a href="home.php"><img src="../assets/img/profile_picture.png" alt="Profile Picture"></a>
            <span><?php echo $_SESSION['email']; ?></span>
        </div>
        <ul>
            <li><a id="listar-btn" href="#">Meu Perfil</a></li>
            <li><a href="#">Vagas Disponíveis</a></li>
            <li><a href="../scripts/logout_process.php">Sair</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>HOMEPAGE</h1>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nobis eveniet nostrum facere, corrupti sit mollitia enim. Tempore beatae architecto, unde repellat odit nulla quos natus, fugit vero provident accusantium nihil?.</p>
    </div>
</div>
<script>
    document.getElementById("listar-btn").addEventListener("click", function() {
        document.getElementById("meus-dados-content").style.display = "block";
    });
</script>
</body>
</html>
