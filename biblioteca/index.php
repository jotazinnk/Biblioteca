<?php

session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login/login.php");
    exit();
}

include "conexao.php";

$nomeUsuario = $_SESSION['nome'];

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

    <title>Biblioteca</title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

    <style>

        header {
            position: relative;
        }

        .perfil {

            position: absolute;

            top: 35px;

            right: 50px;

            z-index: 9999;

        }

        .perfil-botao {

            width: 52px !important;

            height: 52px !important;

            min-width: 52px !important;

            min-height: 52px !important;

            padding: 0 !important;

            margin: 0 !important;

            border: none !important;

            border-radius: 50% !important;

            background: white !important;

            color: #be185d !important;

            display: flex !important;

            align-items: center !important;

            justify-content: center !important;

            cursor: pointer;

            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.18);

            transition: 0.2s;

        }

        .perfil-botao:hover {

            transform: scale(1.08);

        }

        .perfil-inicial {

            width: 100%;

            height: 100%;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background: white !important;

            color: #be185d !important;

            font-size: 18px;

            font-weight: bold;

        }

        .perfil-menu {

            position: absolute;

            top: 62px;

            right: 0;

            width: 250px;

            padding: 14px;

            background: white;

            border-radius: 14px;

            box-shadow:
                0 8px 25px rgba(0, 0, 0, 0.20);

            z-index: 10000;

            display: none !important;

        }

        .perfil-menu.ativo {

            display: block !important;

        }

        .perfil-info {

            display: flex;

            align-items: center;

            gap: 12px;

            padding: 8px;

        }

        .perfil-inicial-grande {

            width: 48px;

            height: 48px;

            min-width: 48px;

            border-radius: 50%;

            background: #fff1f7;

            color: #be185d;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 18px;

            font-weight: bold;

        }

        .perfil-info strong {

            display: block;

            color: #333;

            font-size: 15px;

            margin-bottom: 4px;

        }

        .perfil-info span {

            display: block;

            color: #999;

            font-size: 12px;

        }

        .perfil-linha {

            height: 1px;

            background: #fce7f3;

            margin: 10px 0;

        }

        .perfil-menu a {

            display: block;

            padding: 11px 12px;

            color: #555;

            text-decoration: none;

            border-radius: 8px;

            font-size: 14px;

            font-weight: bold;

            transition: 0.2s;

        }

        .perfil-menu a:hover {

            background: #fff1f7;

            color: #be185d;

        }

        .titulo {

            margin-top: -15px;

            margin-bottom: 25px;

        }

        .titulo h2 {

            color: #9d174d;

            font-size: 32px;

            margin: 0;

        }

        .titulo h2 strong {

            font-weight: bold;

            color: #be185d;

        }

        @media (max-width: 700px) {

            .perfil {

                top: 25px;

                right: 25px;

            }

            .perfil-menu {

                right: -5px;

            }

        }

    </style>

</head>

<body>

<header>

    <h1>
        📚 Biblioteca
    </h1>

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

    </nav>

    <div class="perfil">

        <button
            type="button"
            class="perfil-botao"
            onclick="abrirPerfil(event)"
        >

            <span class="perfil-inicial">

                <?php echo htmlspecialchars($inicial); ?>

            </span>

        </button>

        <div
            class="perfil-menu"
            id="perfilMenu"
        >

            <div class="perfil-info">

                <div class="perfil-inicial-grande">

                    <?php echo htmlspecialchars($inicial); ?>

                </div>

                <div>

                    <strong>

                        <?php echo htmlspecialchars($nomeUsuario); ?>

                    </strong>

                    <span>
                        Cliente
                    </span>

                </div>

            </div>

            <div class="perfil-linha"></div>

            <a href="perfil.php">

                👤 Meu perfil

            </a>

        </div>

    </div>

</header>

<main class="container">

    <div class="titulo">

        <h2>

            Seja bem-vindo,

            <strong>
                <?php echo htmlspecialchars($nomeUsuario); ?>!
            </strong>

        </h2>

    </div>

    <div class="cards">

        <div class="card">

            <div class="icone">
                📖
            </div>

            <h3>
                Livros
            </h3>

            <p>
                Consulte e gerencie os livros cadastrados
                na biblioteca.
            </p>

            <a
                href="livro/listar.php"
                class="btn"
            >
                Ver livros
            </a>

        </div>

        <div class="card">

            <div class="icone">
                👤
            </div>

            <h3>
                Leitores
            </h3>

            <p>
                Consulte e gerencie os leitores cadastrados.
            </p>

            <a
                href="leitor/listar.php"
                class="btn"
            >
                Ver leitores
            </a>

        </div>

    </div>

</main>

<footer>

    <p>
        © 2026 - Sistema de Biblioteca
    </p>

</footer>

<script>

function abrirPerfil(event) {

    event.stopPropagation();

    const menu = document.getElementById("perfilMenu");

    menu.classList.toggle("ativo");

}

document.addEventListener("click", function(event) {

    const perfil = document.querySelector(".perfil");

    const menu = document.getElementById("perfilMenu");

    if (!perfil.contains(event.target)) {

        menu.classList.remove("ativo");

    }

});

</script>

</body>

</html>