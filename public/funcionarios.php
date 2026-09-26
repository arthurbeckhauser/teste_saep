<?php

require_once "../conexao.php";

$mensagem = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if ($nome === "" || $email === "") {
        $erro = "Preencha todos os campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Digite um e-mail válido.";
    } else {

        $sql = "INSERT INTO funcionarios (nome, email) VALUES (?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nome, $email);

        if ($stmt->execute()) {
            $mensagem = "cadastro concluído com sucesso";
        } else {
            $erro = "Erro ao cadastrar funcionário.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Funcionários</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            margin: 0;
            background-color: #FFFFFF;
            color: #000000;
        }

        header {
            background-color: #0A7D5A;
            color: #FFFFFF;
            padding: 20px;
        }

        header h1 {
            margin: 0;
        }

        nav {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        nav a {
            color: #FFFFFF;
            text-decoration: none;
        }

        main {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
        }

        h2 {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #000000;
            border-radius: 5px;
        }

        button {
            background-color: #0A7D5A;
            color: #FFFFFF;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .sucesso {
            background-color: #0A7D5A;
            color: #FFFFFF;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .erro {
            background-color: #000000;
            color: #FFFFFF;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<header>

    <h1>Sistema de Reposição de Medicamentos</h1>

    <nav>
        <a href="index.php">Início</a>
        <a href="funcionarios.php">Funcionários</a>
    </nav>

</header>

<main>

    <h2>Cadastro de Funcionários</h2>

    <?php if ($mensagem !== ""): ?>
        <div class="sucesso">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <?php if ($erro !== ""): ?>
        <div class="erro">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label for="nome">Nome</label>

        <input
            type="text"
            id="nome"
            name="nome"
            required
        >

        <label for="email">E-mail</label>

        <input
            type="email"
            id="email"
            name="email"
            required
        >

        <button type="submit">
            Cadastrar funcionário
        </button>

    </form>

</main>

</body>

</html>