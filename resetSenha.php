<?php

session_start();
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    // Prepare the SQL statement to update the password
    $sql = "UPDATE usuarios SET pass = ? WHERE user = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }

    $stmt->bind_param("ss", $pass, $user);
    $success = $stmt->execute();

    if ($success) {
        // You might want to check if any rows were affected to confirm a successful update
        if ($stmt->affected_rows > 0) {
            // Redirect to login page or another page
            header('Location: login.php');
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
            </form>
        </div>
    </div>
</div>

</body>
</html>
