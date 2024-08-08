<?php

session_start();
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    $sql = "UPDATE usuarios SET pass = ? WHERE user = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }

    $stmt->bind_param("ss", $pass, $user);
    $success = $stmt->execute();

    if ($success) {
        if ($stmt->affected_rows > 0) {
            header('Location: login.html');
            exit;
        } else {
            $erro = "Nenhum usuário encontrado com esse nome!";
        }
    } else {
        die('Erro na execução da consulta: ' . $stmt->error);
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recuperar Senha</title>
    <link href="S4 LOGO.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 500px;
        }
        .centered-text {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="resetSenha.php" method="POST" class="p-4 border shadow-lg rounded form-container">
                <div class="text-center mb-4">
                    <img src="S4 LOGO.png" alt="S4 Logo" width="200" height="200">
                    <h1 class="mt-3">Recuperar Senha</h1>
                </div>
                <div class="mb-3">
                    <label for="user" class="form-label">Usuário</label>
                    <input type="text" class="form-control" name="user" id="user" required placeholder="Insira o nome de usuário">
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" name="pass" id="pass" required placeholder="Insira a nova senha">
                </div>
                <button type="submit" class="btn btn-primary d-block mx-auto">Salvar</button>
                <p class="text-center"><a href="login.html" class="btn btn-success">Voltar
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                    <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                </svg>
            </a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
