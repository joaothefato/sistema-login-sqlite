<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$nome = htmlspecialchars($_SESSION["usuario_nome"], ENT_QUOTES, "UTF-8");
$email = htmlspecialchars($_SESSION["usuario_email"], ENT_QUOTES, "UTF-8");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área Restrita</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 0;
        }

        header {
            background: #ff5f1f;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            width: 600px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        h1 {
            color: #333;
        }

        p {
            font-size: 18px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            background: #ff5f1f;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 6px;
        }

        a:hover {
            background: #e64a10;
        }
    </style>
</head>
<body>

<header>
    <h2>Sistema com Cadastro e Login</h2>
</header>

<div class="container">
    <h1>Área Restrita</h1>

    <p>Bem-vindo, <strong><?= $nome ?></strong>!</p>
    <p>Seu e-mail cadastrado é: <strong><?= $email ?></strong></p>

    <p>Você está logado com sucesso.</p>

    <a href="logout.php">Sair</a>
</div>

</body>
</html>
