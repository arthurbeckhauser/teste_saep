<div class="card">

    <p>
        <strong>Medicamento:</strong>
        <?= htmlspecialchars($pedido["medicamento"]) ?>
    </p>

    <p>
        <strong>Quantidade:</strong>
        <?= htmlspecialchars($pedido["quantidade"]) ?>
    </p>

    <p>
        <strong>Categoria:</strong>
        <?= htmlspecialchars($pedido["categoria"]) ?>
    </p>

    <p class="<?= $pedido["urgencia"] === "alta" ? "alta" : "" ?>">
        <strong>Urgência:</strong>
        <?= htmlspecialchars($pedido["urgencia"]) ?>
    </p>

    <p>
        <strong>Funcionário:</strong>
        <?= htmlspecialchars($pedido["funcionario"]) ?>
    </p>

    <div class="acoes">

        <!-- EDITAR -->
        <a href="pedidos.php?id=<?= $pedido["id"] ?>">
            Editar
        </a>


        <!-- EXCLUIR -->
        <form
            method="POST"
            action="excluir_pedido.php"
            onsubmit="return confirm('Tem certeza que deseja excluir este pedido?');"
        >

            <input
                type="hidden"
                name="id"
                value="<?= $pedido["id"] ?>"
            >

            <button type="submit">
                Excluir
            </button>

        </form>


        <!-- ALTERAR STATUS -->
        <form method="POST" action="alterar_status.php">

            <input
                type="hidden"
                name="id"
                value="<?= $pedido["id"] ?>"
            >

            <select name="status" required>

                <option
                    value="solicitado"
                    <?= $pedido["status"] === "solicitado" ? "selected" : "" ?>
                >
                    Solicitado
                </option>

                <option
                    value="em_separacao"
                    <?= $pedido["status"] === "em_separacao" ? "selected" : "" ?>
                >
                    Em separação
                </option>

                <option
                    value="recebido"
                    <?= $pedido["status"] === "recebido" ? "selected" : "" ?>
                >
                    Recebido
                </option>

            </select>

            <button type="submit">
                Alterar status
            </button>

        </form>

    </div>

</div>