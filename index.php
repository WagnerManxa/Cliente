<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./assets/css/login_style.css" >
</head>
<body>

<div class="container">
<h2>Login</h2>
<form action="scripts/login_process.php" method="post">
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>
    <label for="senha">Senha:</label><br>
    <input type="password" id="senha" name="senha" required><br><br>
    <button type="submit" value="Login">Login</button>
</form>
<p>Ainda não tem uma conta? <a href="./pages/cadastro.php">Cadastre-se</a></p>
</div>
</body>
</html>



