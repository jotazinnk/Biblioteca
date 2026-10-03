<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: login/login.php");
    exit();
}

include "conexao.php";

$idUsuario = $_SESSION["id"];

$sql = "SELECT nome, email FROM clientes WHERE id = ?";

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

$nomeUsuario = $usuario["nome"];

$emailUsuario = $usuario["email"];

$inicial = strtoupper(substr($nomeUsuario, 0, 1));

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Meu Perfil - Biblioteca</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >


    <style>

        .perfil-pagina {

            width: 78%;

            max-width: 1000px;

            margin: 45px auto;

        }

        .perfil-cabecalho {

            background: linear-gradient(
                135deg,
                #ec4899,
                #be185d
            );

            border-radius: 20px;

            padding: 35px;

            color: white;

            box-shadow:
                0 10px 30px rgba(190, 24, 93, 0.20);

            display: flex;

            align-items: center;

            gap: 25px;

            margin-bottom: 25px;

        }

        .perfil-avatar {

            width: 100px;

            height: 100px;

            min-width: 100px;

            border-radius: 50%;

            background: white;

            color: #be185d;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 42px;

            font-weight: bold;

            box-shadow:
                0 6px 18px rgba(0, 0, 0, 0.20);

        }


        .perfil-cabecalho h2 {

            margin: 0 0 7px 0;

            font-size: 30px;

        }


        .perfil-cabecalho p {

            margin: 0;

            font-size: 15px;

            opacity: 0.9;

        }

        .perfil-card {

            background: white;

            border: 1px solid #f8bfd5;

            border-radius: 18px;

            padding: 30px;

            box-shadow:
                0 6px 20px rgba(190, 24, 93, 0.08);

        }


        .perfil-card-titulo {

            color: #be185d;

            font-size: 22px;

            font-weight: bold;

            margin-bottom: 25px;

        }

        .perfil-campo {

            display: flex;

            align-items: center;

            gap: 18px;

            padding: 18px 0;

            border-bottom: 1px solid #fce7f3;

        }


        .perfil-campo:last-child {

            border-bottom: none;

        }


        .perfil-icone {

            width: 48px;

            height: 48px;

            min-width: 48px;

            border-radius: 12px;

            background: #fff1f7;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 22px;

        }


        .perfil-campo-info {

            flex: 1;

        }


        .perfil-campo-label {

            display: block;

            color: #9f7b89;

            font-size: 13px;

            margin-bottom: 5px;

        }


        .perfil-campo-valor {

            display: block;

            color: #333;

            font-size: 16px;

            font-weight: bold;

            word-break: break-word;

        }

        .perfil-badge {

            display: inline-block;

            padding: 6px 14px;

            border-radius: 20px;

            background: #fff1f7;

            color: #be185d;

            font-size: 13px;

            font-weight: bold;

        }

        .perfil-acoes {

            display: flex;

            gap: 12px;

            margin-top: 25px;

            flex-wrap: wrap;

        }

        .perfil-btn {

            display: inline-block;

            padding: 12px 22px;

            border-radius: 9px;

            text-decoration: none;

            font-size: 14px;

            font-weight: bold;

            transition: 0.2s;

        }

        .perfil-btn-editar {

            background: #ec4899;

            color: white;

        }


        .perfil-btn-editar:hover {

            background: #db2777;

            color: white;

            transform: translateY(-2px);

        }

        .perfil-btn-voltar {

            background: #fce7f3;

            color: #be185d;

        }


        .perfil-btn-voltar:hover {

            background: #fbcfe8;

            color: #be185d;

            transform: translateY(-2px);

        }

        .perfil-btn-sair {

            background: #be185d;

            color: white;

        }


        .perfil-btn-sair:hover {

            background: #9d174d;

            color: white;

            transform: translateY(-2px);

        }

        @media (max-width: 768px) {

            .perfil-pagina {

                width: 92%;

                margin: 30px auto;

            }


            .perfil-cabecalho {

                padding: 25px;

                flex-direction: column;

                text-align: center;

            }


            .perfil-cabecalho h2 {

                font-size: 25px;

            }


            .perfil-card {

                padding: 22px;

            }

        }


        @media (max-width: 450px) {

            .perfil-campo {

                align-items: flex-start;

            }


            .perfil-icone {

                width: 42px;

                height: 42px;

                min-width: 42px;

            }


            .perfil-acoes {

                flex-direction: column;

            }


            .perfil-btn {

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

<main class="perfil-pagina">


    <!-- CABEÇALHO DO PERFIL -->

    <section class="perfil-cabecalho">


        <div class="perfil-avatar">

            <?php echo htmlspecialchars($inicial); ?>

        </div>


        <div>

            <h2>

                <?php echo htmlspecialchars($nomeUsuario); ?>

            </h2>


            <p>

                👤 Meu perfil

            </p>

        </div>


    </section>

    <section class="perfil-card">


        <div class="perfil-card-titulo">

            Informações da conta

        </div>

        <div class="perfil-campo">


            <div class="perfil-icone">

                👤

            </div>


            <div class="perfil-campo-info">

                <span class="perfil-campo-label">

                    Nome completo

                </span>


                <span class="perfil-campo-valor">

                    <?php echo htmlspecialchars($nomeUsuario); ?>

                </span>

            </div>


        </div>

        <div class="perfil-campo">


            <div class="perfil-icone">

                📧

            </div>


            <div class="perfil-campo-info">

                <span class="perfil-campo-label">

                    E-mail

                </span>


                <span class="perfil-campo-valor">

                    <?php echo htmlspecialchars($emailUsuario); ?>

                </span>

            </div>


        </div>

        <div class="perfil-campo">


            <div class="perfil-icone">

                ⭐

            </div>


            <div class="perfil-campo-info">

                <span class="perfil-campo-label">

                    Tipo de conta

                </span>


                <span class="perfil-badge">

                    Cliente

                </span>

            </div>


        </div>

        <div class="perfil-acoes">

            <a
                href="editar_perfil.php"
                class="perfil-btn perfil-btn-editar"
            >

                ✏️ Editar perfil

            </a>

            <a
                href="index.php"
                class="perfil-btn perfil-btn-voltar"
            >

                ← Voltar para biblioteca

            </a>

            <a
                href="login/logout.php"
                class="perfil-btn perfil-btn-sair"
            >

                🚪 Sair da conta

            </a>


        </div>


    </section>


</main>

<footer>

    <p>

        © 2026 - Sistema de Biblioteca

    </p>

</footer>


</body>

</html>