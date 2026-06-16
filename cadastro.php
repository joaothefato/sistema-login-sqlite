<?php
session_start();
require_once "db.php";

if (isset($_SESSION['usuario_id'])) {
    header("Location: area_restrita.php");
    exit;
}

$erros = [];
$nome = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $confirmarSenha = $_POST["confirmar_senha"] ?? "";

    if (empty($nome)) {
        $erros[] = "O nome é obrigatório.";
    }

    if (empty($email)) {
        $erros[] = "O e-mail é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Digite um e-mail válido.";
    }

    if (empty($senha)) {
        $erros[] = "A senha é obrigatória.";
    } elseif (strlen($senha) < 6) {
        $erros[] = "A senha deve ter pelo menos 6 caracteres.";
    }

    if ($senha !== $confirmarSenha) {
        $erros[] = "As senhas não conferem.";
    }

    if (empty($erros)) {
        $verificar = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $verificar->bindValue(":email", $email);
        $verificar->execute();

        if ($verificar->fetch()) {
            $erros[] = "Este e-mail já está cadastrado.";
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(":nome", $nome);
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":senha", $senhaHash);
            $stmt->execute();

            header("Location: login.php?cadastro=sucesso");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 400px;
            margin: 80px auto;
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
            color: #fff;
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
    <h1>Cadastro</h1>

    <?php if (!empty($erros)): ?>
        <div class="erro">
            <?php foreach ($erros as $erro): ?>
                <p><?= limparTexto($erro) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= limparTexto($nome) ?>" required>

        <label>E-mail:</label>
        <input type="email" name="email" value="<?= limparTexto($email) ?>" required>

        <label>Senha:</label>
        <input type="password" name="senha" required>

        <label>Confirmar senha:</label>
        <input type="password" name="confirmar_senha" required>

        <button type="submit">Cadastrar</button>
    </form>

    <div class="link">
        <p>Já tem conta? <a href="login.php">Entrar</a></p>
    </div>
</div>

</body>
</html>
