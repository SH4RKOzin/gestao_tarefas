<?php
session_start();
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $email = $_POST['email'];
    $imagem = "user.png";
    // Verifica se o usuário já existe
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE user = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = "Usuário já existe.";
        header('Location: criarusuario.php');
        exit;
    }

    $stmt->close();

    // Insere o novo usuário

    $stmt = $conn->prepare("INSERT INTO usuarios (user, pass, email, imagem) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user, $pass, $email, $imagem);

    if ($stmt->execute()) {
        // Obtenha o ID do usuário recém-criado
        $user_id = $stmt->insert_id;
        $stmt->close();

        // Cria a tabela de tarefas para o novo usuário
        $create_table_sql = "CREATE TABLE usuario_" . $user_id . "_tarefas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tarefa VARCHAR(255) NOT NULL,
            data_inicio DATE,
            data_fim DATE,
            estado VARCHAR(255)
        )";

        if ($conn->query($create_table_sql) === TRUE) {
            $_SESSION['success_message'] = "Usuário criado com sucesso.";
            header('Location: login.php');
            exit;
        } else {
            $_SESSION['error_message'] = "Erro ao criar tabela de tarefas.";
            header('Location: criarusuario.php');
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Erro ao criar usuário.";
        header('Location: criarusuario.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Usuário</title>
    <link href="S4 LOGO.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<style>
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
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-danger mt-4"><?php echo $_SESSION['error_message']; ?></div>
            </div>
        </div>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="criarusuario.php" method="POST" class="p-4 border shadow-lg rounded mt-4">
                <div class="text-center mb-4">
                    <img src="S4 LOGO.png" alt="S4 Logo" width="200" height="200">
                    <h1 class="mt-3">Criar Usuário</h1>
                </div>
                <div class="mb-3">
                    <label for="user" class="form-label">Usuário</label>
                    <input type="text" class="form-control" name="user" id="user" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Senha</label>
                    <input type="password" class="form-control" name="pass" id="pass" required>
                </div>
                <button type="submit" class="btn btn-primary d-block mx-auto">Criar Usuário</button>
                <p class="mt-3 text-center">Já possui uma conta? <a href="login.php">Faça login</a>.</p>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-9anC3EepQ3LKW0zQf5L5Ive2f0WmE9zuXFvrDhUto29T95rQD+/ZvsKbF0JDLiXt" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-1Y3PqNigMqN4DKP8SivXQODV5OPoA1pM9Y2OoDq9bsA4DlOi+CIyTE5lMzyfc6+C" crossorigin="anonymous"></script>
</body>
</html>
