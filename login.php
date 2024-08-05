<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="S4 LOGO.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Apenas para ajuste de centralização */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            max-width: 500px; /* Ajuste o valor conforme necessário */
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
            <form action="login.php" method="POST" class="p-4 border shadow-lg rounded form-container">
                <div class="text-center mb-4">
                    <img src="S4 LOGO.png" alt="S4 Logo" width="200" height="200">
                    <h1 class="mt-3">Faça Login</h1>
                </div>
                <div class="mb-3">
                    <label for="user" class="form-label">User</label>
                    <input type="text" class="form-control" name="user" id="user" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Senha</label>
                    <input type="password" class="form-control" name="pass" id="pass" required>
                    <p>Esqueceu a senha?<a href="resetSenha.php"> Clique aqui</a></p>
                </div>
                <button type="submit" class="btn btn-primary d-block mx-auto">Login</button>
                <hr>
                <p class="mt-3 centered-text">Não tem uma conta? <a href="criarusuario.php">Crie uma conta</a>.</p>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<?php
session_start();
include("conexao.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    // Prepara a consulta SQL para obter o usuário pelo nome de usuário e senha
    $sql = "SELECT id, user, tipo FROM usuarios WHERE user = ? AND pass = ?";
    $stmt = $conn->prepare($sql);

    // Verifica se a preparação da consulta foi bem-sucedida
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }

    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['user'] = $usuario['user'];
        $_SESSION['tipo'] = $usuario['tipo'];
        $stmt->close(); // Fecha o statement

        if ($usuario['tipo'] == 'Corporativa') {
            header("location: ./admin/index.php");
            exit;
         } else {
            header("location: index.php");
            exit;
         }
      } else {
         $error[] = "Falha ao logar! E-mail ou senha incorretos";
      }
   }


?>

