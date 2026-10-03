<?php

session_start();

include "../conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    if (empty($email) || empty($senha)) {

        $mensagem = "Preencha todos os campos!";

    } else {

        $sql = "SELECT * FROM clientes WHERE email = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {

            $usuario = $resultado->fetch_assoc();

            if (password_verify($senha, $usuario["senha"])) {

                $_SESSION["id"] = $usuario["id"];

                $_SESSION["nome"] = $usuario["nome"];

                $_SESSION["email"] = $usuario["email"];

                header("Location: ../index.php");

                exit();

            } else {

                $mensagem = "Email ou senha incorretos.";

            }

        } else {

            $mensagem = "Email ou senha incorretos.";

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

    <title>Login - Biblioteca</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="login-container">

    <div class="login-box">

        <div class="login-icon">
            📚
        </div>

        <h1>Biblioteca</h1>

        <p class="login-subtitle">
            Entre na sua conta
        </p>


        <?php if (!empty($mensagem)) { ?>

            <div class="error">
                <?php echo $mensagem; ?>
            </div>

        <?php } ?>


        <form method="POST">

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="seu@email.com"
                required
            >


            <label>Senha</label>

            <input
                type="password"
                name="senha"
                placeholder="Sua senha"
                required
            >


            <button type="submit">
                Entrar
            </button>

        </form>


        <p class="login-register">

            Ainda não possui uma conta?

            <a href="registro.php">
                Criar conta
            </a>

        </p>

    </div>

</div>

</body>

</html>