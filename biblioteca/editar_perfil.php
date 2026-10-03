<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login/login.php");
    exit();
}

include "conexao.php";

$idUsuario = $_SESSION["id"];

$mensagem = "";
$tipoMensagem = "";

$sql = "SELECT nome, email, senha FROM clientes WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $idUsuario);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows != 1) {

    session_destroy();

    header("Location: login/login.php");

    exit();
}


$usuario = $resultado->fetch_assoc();

$stmt->close();


$nomeAtual = $usuario["nome"];
$emailAtual = $usuario["email"];
$senhaAtual = $usuario["senha"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);

    $senhaAtualDigitada = $_POST["senha_atual"] ?? "";
    $novaSenha = $_POST["nova_senha"] ?? "";
    $confirmarSenha = $_POST["confirmar_senha"] ?? "";

    if (empty($nome)) {

        $mensagem = "Digite seu nome.";
        $tipoMensagem = "erro";

    }

    elseif (empty($email)) {

        $mensagem = "Digite seu e-mail.";
        $tipoMensagem = "erro";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $mensagem = "Digite um e-mail válido.";
        $tipoMensagem = "erro";

    }

    else {

        $sqlEmail = "
            SELECT id
            FROM clientes
            WHERE email = ?
            AND id != ?
        ";

        $stmtEmail = $conn->prepare($sqlEmail);

        $stmtEmail->bind_param(
            "si",
            $email,
            $idUsuario
        );

        $stmtEmail->execute();

        $resultadoEmail = $stmtEmail->get_result();


        if ($resultadoEmail->num_rows > 0) {

            $mensagem = "Este e-mail já está sendo usado por outra conta.";
            $tipoMensagem = "erro";

        }


        $stmtEmail->close();
    }

    if (empty($mensagem)) {
        $alterarSenha = false;

        if (
            !empty($senhaAtualDigitada) ||
            !empty($novaSenha) ||
            !empty($confirmarSenha)
        ) {

            $alterarSenha = true;

        }

        if ($alterarSenha) {
            if (empty($senhaAtualDigitada)) {

                $mensagem = "Digite sua senha atual para alterar a senha.";
                $tipoMensagem = "erro";

            }

            elseif (!password_verify($senhaAtualDigitada, $senhaAtual)) {

                $mensagem = "A senha atual está incorreta.";
                $tipoMensagem = "erro";

            }

            elseif (empty($novaSenha)) {

                $mensagem = "Digite uma nova senha.";
                $tipoMensagem = "erro";

            }

            elseif (strlen($novaSenha) < 6) {

                $mensagem = "A nova senha deve ter pelo menos 6 caracteres.";
                $tipoMensagem = "erro";

            }

            elseif ($novaSenha !== $confirmarSenha) {

                $mensagem = "A confirmação da nova senha não confere.";
                $tipoMensagem = "erro";

            }

        }

        if (empty($mensagem)) {


            if ($alterarSenha) {

                $novaSenhaHash = password_hash(
                    $novaSenha,
                    PASSWORD_DEFAULT
                );


                $sqlUpdate = "
                    UPDATE clientes
                    SET nome = ?, email = ?, senha = ?
                    WHERE id = ?
                ";

                $stmtUpdate = $conn->prepare($sqlUpdate);

                $stmtUpdate->bind_param(
                    "sssi",
                    $nome,
                    $email,
                    $novaSenhaHash,
                    $idUsuario
                );

            } else {

                $sqlUpdate = "
                    UPDATE clientes
                    SET nome = ?, email = ?
                    WHERE id = ?
                ";

                $stmtUpdate = $conn->prepare($sqlUpdate);

                $stmtUpdate->bind_param(
                    "ssi",
                    $nome,
                    $email,
                    $idUsuario
                );

            }


            if ($stmtUpdate->execute()) {
                $_SESSION["nome"] = $nome;
                $_SESSION["email"] = $email;


                $mensagem = "Perfil atualizado com sucesso!";
                $tipoMensagem = "sucesso";

                $nomeAtual = $nome;
                $emailAtual = $email;

                $senhaAtualDigitada = "";
                $novaSenha = "";
                $confirmarSenha = "";

            } else {

                $mensagem = "Não foi possível atualizar seu perfil.";
                $tipoMensagem = "erro";

            }


            $stmtUpdate->close();

        }

    }

}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar Perfil - Biblioteca</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >


    <style>

        .editar-pagina {

            width: 78%;

            max-width: 900px;

            margin: 45px auto;

        }

        .editar-cabecalho {

            background: linear-gradient(
                135deg,
                #ec4899,
                #be185d
            );

            color: white;

            padding: 30px;

            border-radius: 20px;

            box-shadow:
                0 10px 30px rgba(190, 24, 93, 0.20);

            margin-bottom: 25px;

        }


        .editar-cabecalho h2 {

            margin: 0 0 8px 0;

            font-size: 28px;

        }


        .editar-cabecalho p {

            margin: 0;

            font-size: 15px;

            opacity: 0.9;

        }

        .editar-card {

            background: white;

            border: 1px solid #f8bfd5;

            border-radius: 18px;

            padding: 32px;

            box-shadow:
                0 6px 20px rgba(190, 24, 93, 0.08);

        }

        .editar-secao {

            color: #be185d;

            font-size: 21px;

            font-weight: bold;

            margin-bottom: 22px;

        }

        .editar-campo {

            margin-bottom: 22px;

        }


        .editar-campo label {

            display: block;

            color: #444;

            font-size: 14px;

            font-weight: bold;

            margin-bottom: 8px;

        }


        .editar-input-area {

            position: relative;

        }


        .editar-icone {

            position: absolute;

            left: 15px;

            top: 50%;

            transform: translateY(-50%);

            font-size: 18px;

            pointer-events: none;

        }


        .editar-campo input {

            width: 100%;

            padding: 14px 15px 14px 48px;

            border: 1px solid #f1c4d8;

            border-radius: 10px;

            background: #fffafd;

            color: #333;

            font-size: 15px;

            outline: none;

            transition: 0.2s;

        }


        .editar-campo input:focus {

            border-color: #ec4899;

            background: white;

            box-shadow:
                0 0 0 3px rgba(236, 72, 153, 0.10);

        }


        .editar-campo small {

            display: block;

            margin-top: 7px;

            color: #999;

            font-size: 12px;

        }

        .editar-divisoria {

            height: 1px;

            background: #fce7f3;

            margin: 30px 0;

        }

        .senha-info {

            background: #fff5f9;

            border: 1px solid #fbcfe8;

            border-radius: 12px;

            padding: 15px;

            margin-bottom: 22px;

            color: #9d174d;

            font-size: 13px;

            line-height: 1.5;

        }

        .editar-mensagem {

            padding: 14px 16px;

            border-radius: 10px;

            margin-bottom: 25px;

            font-size: 14px;

            font-weight: bold;

        }


        .editar-mensagem.sucesso {

            background: #ecfdf5;

            border: 1px solid #a7f3d0;

            color: #047857;

        }


        .editar-mensagem.erro {

            background: #fff1f2;

            border: 1px solid #fecdd3;

            color: #be123c;

        }

        .editar-acoes {

            display: flex;

            gap: 12px;

            margin-top: 30px;

            flex-wrap: wrap;

        }


        .editar-btn {

            display: inline-block;

            padding: 13px 22px;

            border-radius: 9px;

            border: none;

            text-decoration: none;

            font-size: 14px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.2s;

        }


        .editar-btn-salvar {

            background: #ec4899;

            color: white;

        }


        .editar-btn-salvar:hover {

            background: #db2777;

            transform: translateY(-2px);

        }


        .editar-btn-voltar {

            background: #fce7f3;

            color: #be185d;

        }


        .editar-btn-voltar:hover {

            background: #fbcfe8;

            color: #be185d;

            transform: translateY(-2px);

        }

        @media (max-width: 768px) {

            .editar-pagina {

                width: 92%;

                margin: 30px auto;

            }


            .editar-cabecalho {

                padding: 25px;

            }


            .editar-card {

                padding: 22px;

            }

        }


        @media (max-width: 500px) {

            .editar-acoes {

                flex-direction: column;

            }


            .editar-btn {

                width: 100%;

                text-align: center;

                box-sizing: border-box;

            }

        }

    </style>

</head>


<body>

<header>

    <h1>📚 Biblioteca</h1>


    <nav>

        <a href="index.php">
            Início
        </a>

        <a href="leitor/listar.php">
            Leitores
        </a>

        <a href="livro/listar.php">
            Livros
        </a>

        <a href="login/logout.php">
            Sair
        </a>

    </nav>

</header>

<main class="editar-pagina">


    <!-- CABEÇALHO DA PÁGINA -->

    <section class="editar-cabecalho">

        <h2>
            ✏️ Editar perfil
        </h2>

        <p>
            Atualize suas informações pessoais e mantenha sua conta sempre atualizada.
        </p>

    </section>

    <section class="editar-card">


        <?php if (!empty($mensagem)) { ?>

            <div class="editar-mensagem <?php echo $tipoMensagem; ?>">

                <?php echo htmlspecialchars($mensagem); ?>

            </div>

        <?php } ?>


        <form method="POST">

            <div class="editar-secao">

                👤 Dados pessoais

            </div>


            <!-- NOME -->

            <div class="editar-campo">

                <label for="nome">
                    Nome completo
                </label>


                <div class="editar-input-area">

                    <span class="editar-icone">
                        👤
                    </span>


                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        value="<?php echo htmlspecialchars($nomeAtual); ?>"
                        placeholder="Digite seu nome completo"
                        maxlength="100"
                        required
                    >

                </div>

            </div>

            <div class="editar-campo">

                <label for="email">
                    E-mail
                </label>


                <div class="editar-input-area">

                    <span class="editar-icone">
                        📧
                    </span>


                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo htmlspecialchars($emailAtual); ?>"
                        placeholder="seu@email.com"
                        maxlength="150"
                        required
                    >

                </div>

            </div>

            <div class="editar-divisoria"></div>

            <div class="editar-secao">

                🔒 Alterar senha

            </div>


            <div class="senha-info">

                💡
                <strong>Quer trocar sua senha?</strong>
                Preencha os três campos abaixo.
                Se não quiser alterar a senha, deixe todos vazios.

            </div>

            <div class="editar-campo">

                <label for="senha_atual">
                    Senha atual
                </label>


                <div class="editar-input-area">

                    <span class="editar-icone">
                        🔑
                    </span>


                    <input
                        type="password"
                        id="senha_atual"
                        name="senha_atual"
                        placeholder="Digite sua senha atual"
                    >

                </div>

            </div>

            <div class="editar-campo">

                <label for="nova_senha">
                    Nova senha
                </label>


                <div class="editar-input-area">

                    <span class="editar-icone">
                        🔒
                    </span>


                    <input
                        type="password"
                        id="nova_senha"
                        name="nova_senha"
                        placeholder="Digite sua nova senha"
                        minlength="6"
                    >

                </div>


                <small>
                    A nova senha deve possuir pelo menos 6 caracteres.
                </small>

            </div>

            <div class="editar-campo">

                <label for="confirmar_senha">
                    Confirmar nova senha
                </label>


                <div class="editar-input-area">

                    <span class="editar-icone">
                        🔐
                    </span>


                    <input
                        type="password"
                        id="confirmar_senha"
                        name="confirmar_senha"
                        placeholder="Digite novamente sua nova senha"
                    >

                </div>

            </div>

            <div class="editar-acoes">


                <button
                    type="submit"
                    class="editar-btn editar-btn-salvar"
                >

                    💾 Salvar alterações

                </button>


                <a
                    href="perfil.php"
                    class="editar-btn editar-btn-voltar"
                >

                    ← Voltar para o perfil

                </a>


            </div>


        </form>


    </section>


</main>

<footer>

    <p>

        © 2026 - Sistema de Biblioteca - By Miguel José

    </p>

</footer>


</body>

</html>