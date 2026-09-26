<?php

require_once "../conexao.php";

$sql = "SELECT 
            p.id,
            p.medicamento,
            p.quantidade,
            p.categoria,
            p.urgencia,
            p.status,
            f.nome AS funcionario
        FROM pedidos p
        INNER JOIN funcionarios f ON p.funcionario_id = f.id
        ORDER BY 
            CASE p.urgencia
                WHEN 'alta' THEN 1
                WHEN 'media' THEN 2
                WHEN 'baixa' THEN 3
            END,
            p.id DESC";

$result = $conn->query($sql);

$pedidos = [
    "solicitado" => [],
    "em_separacao" => [],
    "recebido" => []
];

while ($pedido = $result->fetch_assoc()) {
    $pedidos[$pedido["status"]][] = $pedido;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel de Reposição</title>

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
            padding: 30px;
        }

        h2 {
            margin-bottom: 25px;
        }

        .painel {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .coluna {
            border: 1px solid #000000;
            border-radius: 8px;
            padding: 15px;
            min-height: 400px;
        }

        .coluna h3 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #0A7D5A;
        }

        .card {
            border: 1px solid #000000;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #FFFFFF;
        }

        .card p {
            margin: 7px 0;
        }

        .alta {
            font-weight: bold;
        }

        .acoes {
            margin-top: 15px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .acoes a,
        .acoes button {
            display: block;
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            background-color: #0A7D5A;
            color: #FFFFFF;
            font-size: 14px;
        }

        .acoes select {
            width: 100%;
            padding: 8px;
            border: 1px solid #000000;
            border-radius: 4px;
            background-color: #FFFFFF;
        }

        .vazio {
            text-align: center;
            margin-top: 30px;
        }

        @media (max-width: 900px) {
            .painel {
                grid-template-columns: 1fr;
            }
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

    <h2>Painel de Reposição</h2>

    <div class="painel">

        <!-- SOLICITADO -->
        <div class="coluna">

            <h3>Solicitado</h3>

            <?php if (empty($pedidos["solicitado"])): ?>

                <p class="vazio">Nenhum pedido.</p>

            <?php endif; ?>

            <?php foreach ($pedidos["solicitado"] as $pedido): ?>

                <?php include "card_pedido.php"; ?>

            <?php endforeach; ?>

        </div>


        <!-- EM SEPARAÇÃO -->
        <div class="coluna">

            <h3>Em separação</h3>

            <?php if (empty($pedidos["em_separacao"])): ?>

                <p class="vazio">Nenhum pedido.</p>

            <?php endif; ?>

            <?php foreach ($pedidos["em_separacao"] as $pedido): ?>

                <?php include "card_pedido.php"; ?>

            <?php endforeach; ?>

        </div>


        <!-- RECEBIDO -->
        <div class="coluna">

            <h3>Recebido</h3>

            <?php if (empty($pedidos["recebido"])): ?>

                <p class="vazio">Nenhum pedido.</p>

            <?php endif; ?>

            <?php foreach ($pedidos["recebido"] as $pedido): ?>

                <?php include "card_pedido.php"; ?>

            <?php endforeach; ?>

        </div>

    </div>

</main>

</body>

</html>