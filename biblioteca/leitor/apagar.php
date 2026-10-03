<?php

include "../conexao.php";


if (!isset($_GET["id"]) || empty($_GET["id"])) {

    header("Location: listar.php");
    exit();

}


$nome = $_GET["id"];

$sql = "DELETE FROM leitor WHERE nome = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $nome);


if ($stmt->execute()) {

    $stmt->close();

    header("Location: listar.php");
    exit();

} else {

    echo "Erro ao excluir leitor: " . $stmt->error;

}


$stmt->close();

?>