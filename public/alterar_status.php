<?php

require_once "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$status = $_POST["status"] ?? "";

$statusPermitidos = [
    "solicitado",
    "em_separacao",
    "recebido"
];

if (!$id || !in_array($status, $statusPermitidos, true)) {
    header("Location: index.php");
    exit;
}

$sql = "UPDATE pedidos SET status = ? WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);
$stmt->execute();

$stmt->close();

header("Location: index.php");
exit;