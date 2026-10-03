<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location: ../login/login.php");
    exit();
}

include "../conexao.php";

$sql = "SELECT * FROM leitor";

$resultado = $conn->query($sql);

if (!$resultado) {

    die("Erro ao buscar os leitores: " . $conn->error);

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

    <title>Leitores - Biblioteca</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #fff5f8;
            color: #333;
            min-height: 100vh;
        }

        header {
            background: linear-gradient(135deg, #ec4899, #be185d);
            color: white;
            padding: 35px 50px 28px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 8px 25px rgba(190, 24, 93, 0.20);
        }

        header h1 {
            font-size: 38px;
            margin-bottom: 25px;
            color: white;
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
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 9px;
            font-weight: bold;
            transition: 0.25s;
        }

        nav a:hover {
            background: white;
            color: #be185d;
            transform: translateY(-2px);
        }

        .container {
            width: 78%;
            max-width: 1100px;
            margin: 45px auto;
        }

        .pagina-topo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .pagina-titulo h2 {
            color: #9d174d;
            font-size: 32px;
            margin-bottom: 7px;
        }

        .pagina-titulo p {
            color: #777;
            font-size: 15px;
        }

        .btn-adicionar {
            display: inline-block;
            padding: 13px 22px;
            background: linear-gradient(135deg, #ec4899, #db2777);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 5px 12px rgba(219, 39, 119, 0.20);
            transition: 0.25s;
        }

        .btn-adicionar:hover {
            background: linear-gradient(135deg, #db2777, #be185d);
            transform: translateY(-2px);
        }

        .leitores-card {
            background: white;
            border: 1px solid #f8bfd5;
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 6px 20px rgba(190, 24, 93, 0.08);
        }

        .lista-cabecalho {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 18px;
            margin-bottom: 5px;
            border-bottom: 1px solid #fce7f3;
        }

        .lista-cabecalho h3 {
            color: #be185d;
            font-size: 20px;
        }

        .quantidade {
            background: #fff1f7;
            color: #be185d;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .leitor-item {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 18px 5px;
            border-bottom: 1px solid #fce7f3;
            transition: 0.2s;
        }

        .leitor-item:last-child {
            border-bottom: none;
        }

        .leitor-item:hover {
            background: #fffafd;
        }

        .leitor-avatar {
            width: 52px;
            height: 52px;
            min-width: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff1f7;
            color: #be185d;
            border-radius: 50%;
            font-size: 20px;
            font-weight: bold;
        }

        .leitor-info {
            flex: 1;
            min-width: 0;
        }

        .leitor-nome {
            display: block;
            color: #333;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            word-break: break-word;
        }

        .leitor-descricao {
            color: #999;
            font-size: 13px;
        }

        .leitor-acoes {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-excluir {
            display: inline-block;
            padding: 9px 14px;
            background: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-excluir:hover {
            background: #ffe4e6;
            color: #9f1239;
        }

        .nenhum-leitor {
            text-align: center;
            padding: 50px 20px;
        }

        .nenhum-icone {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .nenhum-leitor h3 {
            color: #be185d;
            font-size: 20px;
            margin-bottom: 8px;
        }

        .nenhum-leitor p {
            color: #888;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .area-voltar {
            margin-top: 22px;
        }

        .btn-voltar {
            display: inline-block;
            padding: 11px 18px;
            background: #fce7f3;
            color: #be185d;
            text-decoration: none;
            border-radius: 9px;
            font-size: 14px;
            font-weight: bold;
            transition: 0.2s;
        }

        .btn-voltar:hover {
            background: #fbcfe8;
            transform: translateY(-2px);
        }

        footer {
            background: #a9154d;
            color: white;
            text-align: center;
            padding: 22px;
            margin-top: 60px;
            border-radius: 20px 20px 0 0;
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

            .pagina-topo {
                align-items: flex-start;
                flex-direction: column;
            }

            .btn-adicionar {
                width: 100%;
                text-align: center;
            }

            .leitores-card {
                padding: 18px;
            }

        }

        @media (max-width: 550px) {

            .leitor-item {
                align-items: flex-start;
            }

            .leitor-acoes {
                flex-direction: column;
            }

            .btn-excluir {
                text-align: center;
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

        <a href="listar.php">
            Leitores
        </a>

        <a href="../livro/listar.php">
            Livros
        </a>

    </nav>

</header>

<main class="container">

    <div class="pagina-topo">

        <div class="pagina-titulo">

            <h2>
                👥 Leitores
            </h2>

            <p>
                Consulte e gerencie os leitores cadastrados na biblioteca.
            </p>

        </div>

        <a
            href="inserir.php"
            class="btn-adicionar"
        >
            ➕ Adicionar leitor
        </a>

    </div>

    <section class="leitores-card">

        <div class="lista-cabecalho">

            <h3>
                Leitores cadastrados
            </h3>

            <span class="quantidade">

                <?php echo $resultado->num_rows; ?>

                <?php

                if ($resultado->num_rows == 1) {
                    echo " leitor";
                } else {
                    echo " leitores";
                }

                ?>

            </span>

        </div>

        <?php if ($resultado->num_rows > 0) { ?>

            <?php foreach ($resultado as $leitor) { ?>

                <?php

                $nomeLeitor = $leitor["nome"];

                $inicialLeitor = strtoupper(
                    substr($nomeLeitor, 0, 1)
                );

                ?>

                <div class="leitor-item">

                    <div class="leitor-avatar">

                        <?php
                        echo htmlspecialchars($inicialLeitor);
                        ?>

                    </div>

                    <div class="leitor-info">

                        <span class="leitor-nome">

                            <?php
                            echo htmlspecialchars($nomeLeitor);
                            ?>

                        </span>

                        <span class="leitor-descricao">
                            Leitor cadastrado na biblioteca
                        </span>

                    </div>

                    <div class="leitor-acoes">

                        <a
                            href="apagar.php?id=<?php echo urlencode($nomeLeitor); ?>"
                            class="btn-excluir"
                            onclick="return confirmarExclusao();"
                        >
                            🗑️ Excluir
                        </a>

                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <div class="nenhum-leitor">

                <div class="nenhum-icone">
                    👥
                </div>

                <h3>
                    Nenhum leitor cadastrado
                </h3>

                <p>
                    Ainda não existem leitores cadastrados na biblioteca.
                </p>

                <a
                    href="inserir.php"
                    class="btn-adicionar"
                >
                    ➕ Cadastrar primeiro leitor
                </a>

            </div>

        <?php } ?>

    </section>

    <div class="area-voltar">

        <a
            href="../index.php"
            class="btn-voltar"
        >
            ← Voltar para biblioteca
        </a>

    </div>

</main>

<footer>

    <p>
        © 2026 - Sistema de Biblioteca
    </p>

</footer>

<script>

function confirmarExclusao() {

    return confirm(
        "Tem certeza que deseja excluir este leitor?"
    );

}

</script>

</body>

</html>