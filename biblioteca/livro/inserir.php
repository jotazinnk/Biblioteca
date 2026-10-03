<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login/login.php");
    exit();
}

include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nomeLivro = trim($_POST["nome_livro"]);

    if (empty($nomeLivro)) {

        echo "<script>
                alert('Digite o nome do livro.');
                window.location.href = 'inserir.php';
              </script>";

        exit();

    }

    $sql = "INSERT INTO livro (nome_livro) VALUES (?)";

    $stmt = $conn->prepare($sql);


    if (!$stmt) {

        die(
            "Erro ao preparar o cadastro: "
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

    } else {

        $erro = $stmt->error;

        $stmt->close();

        die(
            "Erro ao cadastrar o livro: "
            . $erro
        );

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

    <title>Adicionar Livro - Biblioteca</title>


    <style>

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

        }


        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #fff5f8;

            color: #333;

            min-height: 100vh;

        }

        header {

            background:
                linear-gradient(
                    135deg,
                    #ec4899,
                    #be185d
                );

            color: white;

            padding: 35px 50px 28px;

            border-radius:
                0 0 20px 20px;

            box-shadow:
                0 8px 25px
                rgba(190, 24, 93, 0.20);

        }


        header h1 {

            font-size: 38px;

            margin-bottom: 25px;

        }

        nav {

            display: flex;

            gap: 12px;

            flex-wrap: wrap;

        }


        nav a {

            display: inline-block;

            padding: 12px 24px;

            color: white;

            text-decoration: none;

            background:
                rgba(255, 255, 255, 0.15);

            border:
                1px solid
                rgba(255, 255, 255, 0.25);

            border-radius: 9px;

            font-weight: bold;

            transition: 0.25s;

        }


        nav a:hover {

            background: white;

            color: #be185d;

            transform:
                translateY(-2px);

        }

        .container {

            width: 78%;

            max-width: 800px;

            margin: 45px auto;

        }

        .pagina-topo {

            margin-bottom: 25px;

        }


        .pagina-topo h2 {

            color: #9d174d;

            font-size: 32px;

            margin-bottom: 7px;

        }


        .pagina-topo p {

            color: #777;

            font-size: 15px;

        }

        .formulario-card {

            background: white;

            border:
                1px solid #f8bfd5;

            border-radius: 18px;

            padding: 30px;

            box-shadow:
                0 6px 20px
                rgba(190, 24, 93, 0.08);

        }

        .campo {

            margin-bottom: 22px;

        }


        .campo label {

            display: block;

            color: #831843;

            font-weight: bold;

            font-size: 14px;

            margin-bottom: 8px;

        }


        .campo input {

            width: 100%;

            padding: 14px 15px;

            border:
                1px solid #f3b5ce;

            border-radius: 10px;

            font-size: 15px;

            outline: none;

            transition: 0.2s;

        }


        .campo input:focus {

            border-color: #db2777;

            box-shadow:
                0 0 0 3px
                rgba(219, 39, 119, 0.10);

        }

        .botoes {

            display: flex;

            gap: 12px;

            margin-top: 10px;

        }


        .btn-salvar {

            flex: 1;

            border: none;

            padding: 14px;

            background:
                linear-gradient(
                    135deg,
                    #ec4899,
                    #db2777
                );

            color: white;

            border-radius: 10px;

            font-size: 15px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.25s;

        }


        .btn-salvar:hover {

            background:
                linear-gradient(
                    135deg,
                    #db2777,
                    #be185d
                );

            transform:
                translateY(-2px);

        }


        .btn-cancelar {

            flex: 1;

            padding: 14px;

            background: #fce7f3;

            color: #be185d;

            text-decoration: none;

            border-radius: 10px;

            text-align: center;

            font-size: 15px;

            font-weight: bold;

            transition: 0.25s;

        }


        .btn-cancelar:hover {

            background: #fbcfe8;

            transform:
                translateY(-2px);

        }

        footer {

            background: #a9154d;

            color: white;

            text-align: center;

            padding: 22px;

            margin-top: 60px;

            border-radius:
                20px 20px 0 0;

        }


        footer p {

            font-size: 13px;

        }

        @media (max-width: 768px) {

            header {

                padding: 28px 25px;

            }


            header h1 {

                font-size: 30px;

            }


            .container {

                width: 92%;

                margin: 30px auto;

            }


            .formulario-card {

                padding: 22px;

            }


            .botoes {

                flex-direction: column;

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

        <a href="../index.php">
            Início
        </a>

        <a href="../leitor/listar.php">
            Leitores
        </a>

        <a href="listar.php">
            Livros
        </a>

    </nav>

</header>

<main class="container">


    <div class="pagina-topo">

        <h2>
            📖 Adicionar livro
        </h2>

        <p>
            Cadastre um novo livro na biblioteca.
        </p>

    </div>


    <section class="formulario-card">


        <form
            method="POST"
            action=""
        >


            <div class="campo">

                <label for="nome_livro">
                    Nome do livro
                </label>

                <input
                    type="text"
                    id="nome_livro"
                    name="nome_livro"
                    placeholder="Digite o nome do livro"
                    required
                    autofocus
                >

            </div>


            <div class="botoes">


                <a
                    href="listar.php"
                    class="btn-cancelar"
                >

                    ← Cancelar

                </a>


                <button
                    type="submit"
                    class="btn-salvar"
                >

                    ➕ Cadastrar livro

                </button>


            </div>


        </form>


    </section>


</main>


<footer>

    <p>
        © 2026 - Sistema de Biblioteca
    </p>

</footer>


</body>

</html>