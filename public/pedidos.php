<?php

require_once "../conexao.php";

$mensagem = "";
$erro = "";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

$modoEdicao = $id !== false && $id !== null;

$pedido = [
    "medicamento" => "",
    "quantidade" => "",
    "categoria" => "",
    "funcionario_id" => "",
    "urgencia" => ""
];


/* BUSCAR PEDIDO PARA EDIÇÃO */

if ($modoEdicao) {

    $sql = "SELECT
                medicamento,
                quantidade,
                categoria,
                funcionario_id,
                urgencia
            FROM pedidos
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {

        $pedido = $resultado->fetch_assoc();

    } else {

        $erro = "Pedido não encontrado.";
        $modoEdicao = false;
    }

    $stmt->close();
}


/* SALVAR */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idPost = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    $modoEdicao = $idPost !== false && $idPost !== null;

    $medicamento = trim($_POST["medicamento"] ?? "");
    $quantidade = $_POST["quantidade"] ?? "";
    $categoria = $_POST["categoria"] ?? "";
    $funcionario_id = $_POST["funcionario_id"] ?? "";
    $urgencia = $_POST["urgencia"] ?? "";

    if (
        $medicamento === "" ||
        $quantidade === "" ||
        $categoria === "" ||
        $funcionario_id === "" ||
        $urgencia === ""
    ) {

        $erro = "Preencha todos os campos.";

    } elseif (
        !filter_var($quantidade, FILTER_VALIDATE_INT) ||
        (int)$quantidade <= 0
    ) {

        $erro = "A quantidade deve ser um número inteiro maior que zero.";

    } else {

        $quantidade = (int)$quantidade;

        if ($modoEdicao) {

            $sql = "UPDATE pedidos
                    SET funcionario_id = ?,
                        medicamento = ?,
                        quantidade = ?,
                        categoria = ?,
                        urgencia = ?
                    WHERE id = ?";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "isissi",
                $funcionario_id,
                $medicamento,
                $quantidade,
                $categoria,
                $urgencia,
                $idPost
            );

            if ($stmt->execute()) {

                header("Location: index.php");
                exit;

            } else {

                $erro = "Erro ao atualizar pedido.";
            }

            $stmt->close();

        } else {

            $sql = "INSERT INTO pedidos
                    (funcionario_id, medicamento, quantidade, categoria, urgencia)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "isiss",
                $funcionario_id,
                $medicamento,
                $quantidade,
                $categoria,
                $urgencia
            );

            if ($stmt->execute()) {

                $mensagem = "cadastro concluído com sucesso";

            } else {

                $erro = "Erro ao cadastrar pedido.";
            }

            $stmt->close();
        }
    }

    $pedido["medicamento"] = $medicamento;
    $pedido["quantidade"] = $quantidade;
    $pedido["categoria"] = $categoria;
    $pedido["funcionario_id"] = $funcionario_id;
    $pedido["urgencia"] = $urgencia;
}


/* FUNCIONÁRIOS */

$sqlFuncionarios = "SELECT id, nome FROM funcionarios ORDER BY nome";
$resultFuncionarios = $conn->query($sqlFuncionarios);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $modoEdicao ? "Editar Pedido" : "Cadastro de Pedidos" ?>
    </title>

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
            max-width: 650px;
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

        input,
        select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #000000;
            border-radius: 5px;
            background-color: #FFFFFF;
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
        <a href="index.php">Painel</a>
        <a href="funcionarios.php">Funcionários</a>
        <a href="pedidos.php">Cadastrar pedido</a>
    </nav>

</header>

<main>

    <h2>
        <?= $modoEdicao ? "Editar Pedido" : "Cadastro de Pedidos de Reposição" ?>
    </h2>

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

        <?php if ($modoEdicao): ?>

            <input
                type="hidden"
                name="id"
                value="<?= $id ?>"
            >

        <?php endif; ?>


        <label for="medicamento">Medicamento</label>

        <input
            type="text"
            id="medicamento"
            name="medicamento"
            value="<?= htmlspecialchars($pedido["medicamento"]) ?>"
            required
        >


        <label for="quantidade">Quantidade</label>

        <input
            type="number"
            id="quantidade"
            name="quantidade"
            min="1"
            step="1"
            value="<?= htmlspecialchars($pedido["quantidade"]) ?>"
            required
        >


        <label for="categoria">Categoria</label>

        <select id="categoria" name="categoria" required>

            <option value="">Selecione uma categoria</option>

            <option value="generico"
                <?= $pedido["categoria"] === "generico" ? "selected" : "" ?>>
                Genérico
            </option>

            <option value="referencia"
                <?= $pedido["categoria"] === "referencia" ? "selected" : "" ?>>
                Referência
            </option>

            <option value="controlado"
                <?= $pedido["categoria"] === "controlado" ? "selected" : "" ?>>
                Controlado
            </option>

            <option value="higiene"
                <?= $pedido["categoria"] === "higiene" ? "selected" : "" ?>>
                Higiene
            </option>

        </select>


        <label for="funcionario_id">Funcionário</label>

        <select id="funcionario_id" name="funcionario_id" required>

            <option value="">Selecione um funcionário</option>

            <?php while ($funcionario = $resultFuncionarios->fetch_assoc()): ?>

                <option
                    value="<?= $funcionario["id"] ?>"
                    <?= $pedido["funcionario_id"] == $funcionario["id"] ? "selected" : "" ?>
                >
                    <?= htmlspecialchars($funcionario["nome"]) ?>
                </option>

            <?php endwhile; ?>

        </select>


        <label for="urgencia">Urgência</label>

        <select id="urgencia" name="urgencia" required>

            <option value="">Selecione a urgência</option>

            <option value="baixa"
                <?= $pedido["urgencia"] === "baixa" ? "selected" : "" ?>>
                Baixa
            </option>

            <option value="media"
                <?= $pedido["urgencia"] === "media" ? "selected" : "" ?>>
                Média
            </option>

            <option value="alta"
                <?= $pedido["urgencia"] === "alta" ? "selected" : "" ?>>
                Alta
            </option>

        </select>


        <button type="submit">

            <?= $modoEdicao ? "Salvar alterações" : "Cadastrar pedido" ?>

        </button>

    </form>

</main>

</body>

</html>