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

        $sql = "INSERT INTO leitor (nome, email, senha) VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senha);

        if ($stmt->execute()) {

            header("Location: listar.php");
            exit();

        } else {

            $mensagem = "Erro ao cadastrar leitor: " . $stmt->error;
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

    <title>Adicionar Leitor</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #fff5f8;
            min-height: 100vh;
            color: #4a1230;
        }

        header {
            width: 100%;
            background: linear-gradient(135deg, #ec4899, #be185d);
            padding: 18px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(190, 24, 93, 0.20);
        }

        .logo {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        nav {
            display: flex;
            gap: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 15px;
            transition: 0.3s;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.18);
        }

        .container {
            width: 100%;
            max-width: 650px;
            margin: 55px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(190, 24, 93, 0.12);
            border: 1px solid #fce7f3;
        }

        .titulo {
            text-align: center;
            margin-bottom: 30px;
        }

        .icone {
            width: 70px;
            height: 70px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: #fce7f3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        h1 {
            color: #9d174d;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .subtitulo {
            color: #777;
            font-size: 15px;
        }

        .mensagem {
            background: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
            border-radius: 10px;
            padding: 13px 15px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #6b1740;
            font-weight: bold;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #f3c4d9;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            background: #fffafd;
            color: #4a1230;
            transition: 0.3s;
        }

        input::placeholder {
            color: #aaa;
        }

        input:focus {
            border-color: #ec4899;
            background: white;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.12);
        }

        .botoes {
            display: flex;
            gap: 12px;
            margin-top: 28px;
        }

        .btn {
            flex: 1;
            border: none;
            border-radius: 10px;
            padding: 14px 20px;
            font-size: 15px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-cadastrar {
            background: linear-gradient(135deg, #ec4899, #db2777);
            color: white;
            box-shadow: 0 5px 15px rgba(219, 39, 119, 0.25);
        }

        .btn-cadastrar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(219, 39, 119, 0.35);
        }

        .btn-voltar {
            background: #fce7f3;
            color: #9d174d;
        }

        .btn-voltar:hover {
            background: #fbcfe8;
            transform: translateY(-2px);
        }

        .rodape {
            text-align: center;
            margin-top: 25px;
            color: #999;
            font-size: 13px;
        }

        @media (max-width: 600px) {

            header {
                padding: 15px 20px;
                flex-direction: column;
                gap: 12px;
            }

            nav {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .container {
                margin: 30px auto;
            }

            .card {
                padding: 28px 20px;
            }

            h1 {
                font-size: 24px;
            }

            .botoes {
                flex-direction: column;
            }

        }

    </style>

</head>

<body>

    <header>

        <a href="../index.php" class="logo">
            📚 Biblioteca
        </a>

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

        <div class="card">

            <div class="titulo">

                <div class="icone">
                    👤
                </div>

                <h1>Adicionar Leitor</h1>

                <p class="subtitulo">
                    Cadastre um novo leitor na biblioteca
                </p>

            </div>


            <?php if (!empty($mensagem)): ?>

                <div class="mensagem">
                    <?php echo htmlspecialchars($mensagem); ?>
                </div>

            <?php endif; ?>


            <form method="post">

                <div class="form-group">

                    <label for="nome">
                        Nome do leitor
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        placeholder="Digite o nome completo"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="email">
                        E-mail
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="exemplo@email.com"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="senha">
                        Senha
                    </label>

                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        placeholder="Digite uma senha"
                        required
                    >

                </div>


                <div class="botoes">

                    <a href="listar.php" class="btn btn-voltar">
                        ← Voltar
                    </a>

                    <button type="submit" class="btn btn-cadastrar">
                        ✓ Cadastrar leitor
                    </button>

                </div>

            </form>

        </div>


        <div class="rodape">
            Sistema de Biblioteca • Cadastro de leitores
        </div>

    </main>

</body>

</html>