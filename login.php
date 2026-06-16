<?php
session_start();
require_once "db.php";

if (isset($_SESSION['usuario_id'])) {
    header("Location: area_restrita.php");
    exit;
}

$erro = "";
$mensagem = "";

if (isset($_GET["cadastro"]) && $_GET["cadastro"] === "sucesso") {
    $mensagem = "Cadastro realizado com sucesso. Faça login.";
}

if (isset($_GET["logout"]) && $_GET["logout"] === "sucesso") {
    $mensagem = "Você saiu do sistema.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if (empty($email) || empty($senha)) {
        $erro = "Preencha e-mail e senha.";
    } else {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":email", $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario["senha"])) {
            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nome"] = $usuario["nome"];
            $_SESSION["usuario_email"] = $usuario["email"];

            header("Location: area_restrita.php");
            exit;
        } else {
            $erro = "E-mail ou senha inválidos.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 400px;
            margin: 100px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #ff5f1f;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #e64a10;
        }

        .erro {
            background: #ffd6d6;
            color: #900;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .sucesso {
            background: #d6ffd9;
            color: #075c12;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            color: #ff5f1f;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Login</h1>

    <?php if (!empty($mensagem)): ?>
        <div class="sucesso">
            <?= limparTexto($mensagem) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($erro)): ?>
        <div class="erro">
            <?= limparTexto($erro) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>E-mail:</label>
        <input type="email" name="email" required>

        <label>Senha:</label>
        <input type="password" name="senha" required>

        <button type="submit">Entrar</button>
    </form>

    <div class="link">
        <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
    </div>
</div>

</body>
</html>
