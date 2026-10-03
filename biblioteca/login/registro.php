<?php

include "../conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    if (empty($nome) || empty($email) || empty($senha)) {

        $mensagem = "Preencha todos os campos!";

    } else {

        $sql = "SELECT id FROM clientes WHERE email = ?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $mensagem = "Erro ao verificar o cadastro: " . $conn->error;
        } else {

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();

            $stmt->close();

            if ($resultado->num_rows > 0) {

                $mensagem = "Este email já está cadastrado!";

            } else {

                $senhaHash = password_hash(
                    $senha,
                    PASSWORD_DEFAULT
                );

                $conn->begin_transaction();

                try {

                    $sql = "INSERT INTO clientes (nome, email, senha)
                            VALUES (?, ?, ?)";

                    $stmt = $conn->prepare($sql);

                    if (!$stmt) {
                        throw new Exception(
                            "Erro ao preparar cadastro: " . $conn->error
                        );
                    }

                    $stmt->bind_param(
                        "sss",
                        $nome,
                        $email,
                        $senhaHash
                    );

                    if (!$stmt->execute()) {
                        throw new Exception(
                            "Erro ao cadastrar usuário: " . $stmt->error
                        );
                    }

                    $stmt->close();

                    $sql = "SELECT * FROM leitor WHERE email = ?";

                    $stmt = $conn->prepare($sql);

                    if (!$stmt) {
                        throw new Exception(
                            "Erro ao verificar leitor: " . $conn->error
                        );
                    }

                    $stmt->bind_param("s", $email);
                    $stmt->execute();

                    $resultadoLeitor = $stmt->get_result();

                    $stmt->close();

                    if ($resultadoLeitor->num_rows == 0) {

                        $sql = "INSERT INTO leitor (nome, email, senha)
                                VALUES (?, ?, ?)";

                        $stmt = $conn->prepare($sql);

                        if (!$stmt) {
                            throw new Exception(
                                "Erro ao preparar cadastro do leitor: "
                                . $conn->error
                            );
                        }

                        $stmt->bind_param(
                            "sss",
                            $nome,
                            $email,
                            $senhaHash
                        );

                        if (!$stmt->execute()) {
                            throw new Exception(
                                "Erro ao cadastrar leitor: "
                                . $stmt->error
                            );
                        }

                        $stmt->close();
                    }

                    $conn->commit();

                    header("Location: login.php");
                    exit();


                } catch (Exception $e) {

                    $conn->rollback();

                    $mensagem = $e->getMessage();
                }
            }
        }
    }
}

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

    <title>Cadastro - Biblioteca</title>

</head>

<body>

    <div class="login-container">

        <div class="login-box">

            <div class="login-icon">
                📚
            </div>

            <h1>
                Biblioteca
            </h1>


            <p class="login-subtitle">
                Crie sua conta
            </p>

            <?php if (!empty($mensagem)) { ?>

                <div class="error">

                    <?php
                    echo htmlspecialchars($mensagem);
                    ?>

                </div>

            <?php } ?>

            <form action="" method="post">

                <label for="nome">
                    Nome
                </label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Seu nome"
                    required
                >


                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="seu@email.com"
                    required
                >


                <label for="senha">
                    Senha
                </label>

                <input
                    type="password"
                    id="senha"
                    name="senha"
                    placeholder="Sua senha"
                    required
                >


                <button type="submit">
                    Cadastrar
                </button>

            </form>

            <div class="login-register">

                Já possui uma conta?

                <a href="login.php">
                    Entrar
                </a>

            </div>

        </div>

    </div>

</body>

</html>