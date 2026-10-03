<?php

include "../conexao.php";


if (!isset($_GET["id"]) || empty($_GET["id"])) {

    header("Location: listar.php");

    exit();

}


$nomeLivro = $_GET["id"];


$sql = "DELETE FROM livro WHERE nome_livro = ?";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    die(
        "Erro ao preparar exclusão: "
        . $conn->error
    );

}


$stmt->bind_param(
    "s",
    $nomeLivro
);


if ($stmt->execute()) {

    $stmt->close();

    header("Location: listar.php");

    exit();

}


echo "Erro ao excluir livro: "
    . $stmt->error;


$stmt->close();

?>