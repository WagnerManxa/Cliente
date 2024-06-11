<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/login_style.css" >

    <title>Cadastro</title>
</head>
<body>
    <div class="container">
        <a href="../index.php"><button type="button"  >Voltar</button></a><br>
    <h2>Cadastro</h2>
    <form id="cadastroForm" action="../scripts/cadastro_process.php" method="post" enctype="multipart/form-data">
        <label for="tipoCadastro">Escolha o tipo de cadastro:</label>
        <select id="tipoCadastro" name="tipoCadastro">
            <option value="candidato">Candidato</option>
            <option value="empresa">Empresa</option>
        </select><br>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" minlength="8" required><br><br>

        <div id="camposEmpresa" style="display: none;">
            <label for="ramo">Ramo:</label>
            <input type="text" id="ramo" name="ramo"><br>
            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" maxlength="250" name="descricao"><br><br>
        </div>
       
        <button type="submit">Cadastrar</button>
    </form>
    
    </div>
    <script>
        document.getElementById("tipoCadastro").addEventListener("change", function() {
            var tipoCadastro = this.value;
            var camposEmpresa = document.getElementById("camposEmpresa");

            if (tipoCadastro === "candidato") {
                camposEmpresa.style.display = "none";
            } else if (tipoCadastro === "empresa") {
                camposEmpresa.style.display = "block";
            }
        });

    </script>
</body>
</html>
