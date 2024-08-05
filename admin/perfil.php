<?php
session_start();
include("conexao.php");

// Verifica se o usuário está autenticado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Consulta os dados do usuário
$stmt = $conn->prepare("SELECT user, email, pass, imagem FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $user = $row['user'];
    $email = $row['email'];
    $imagem = $row['imagem']; // Assume que o nome do arquivo da imagem está armazenado no banco de dados
} else {
    $_SESSION['error_message'] = "Erro ao carregar informações do perfil.";
    header('Location: index.php');
    exit();
}

// Atualiza as informações do perfil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['user'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];

    // Atualiza a imagem se foi enviada
    if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] === UPLOAD_ERR_OK) {
        $upload_dir = './img/';
        $upload_file = $upload_dir . basename($_FILES["imagem"]["name"]);

        if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $upload_file)) {
            $imagem = basename($_FILES["imagem"]["name"]);

            // Atualiza a imagem no banco de dados
            $sql = "UPDATE usuarios SET imagem=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $imagem, $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Erro ao enviar a imagem.";
        }
    }

    // Atualiza os dados do usuário
    if (!empty($pass)) {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET user=?, email=?, pass=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $user, $email, $hashed_pass, $user_id);
    } else {
        $sql = "UPDATE usuarios SET user=?, email=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $user, $email, $user_id);
    }
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = "Perfil atualizado com sucesso.";
    } else {
        $_SESSION['error_message'] = "Erro ao atualizar perfil.";
    }

    $stmt->close();
}

// Recarrega os dados atualizados após o POST
$stmt = $conn->prepare("SELECT user, email, pass, imagem FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$user = $row['user'];
$email = $row['email'];
$imagem = $row['imagem'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="S4 LOGO.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .footer {
            width: 100%;
            background-color: #343a40;
            text-align: center;
            position: absolute;
            bottom: 0;
        }

        .footer p {
            color: white;
        }

        #photo {
            max-width: 200px;
            max-height: 200px;
            width: auto;
            height: auto;
        }

        .image-container {
            position: relative;
            display: inline-block;
        }

        .btn-upload {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #28a745;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
        }

        .btn-upload input {
            display: none;
        }

        #photo1 {
            max-width: 40px;
            max-height: 40px;
            width: auto;
            height: auto;
        }

        .header a {
            text-decoration: none;
            text-transform: none;
            color: #fff;
            margin-right: 5px;
            margin-left: 15px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="S4 LOGO.png" alt="" width="50px" height="50px">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="QA.php">Q&A</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">SH4RKO</a>
                </li>
            </ul>
            <a href="perfil.php" class="header">
                <img src="<?php echo htmlspecialchars($imagem ? './img/' . htmlspecialchars($imagem) : 'user.png'); ?>" id="photo1" class="img-fluid user-image" alt="User Image">
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="p-4 border shadow-lg rounded mt-4">
                <form method="post" action="perfil.php" enctype="multipart/form-data">
                    <div class="text-center mb-4">
                        <div class="image-container">
                            <img src="<?php echo htmlspecialchars($imagem ? './img/' . htmlspecialchars($imagem) : 'user.png'); ?>" id="photo" class="img-fluid" alt="User Image">
                            <label for="file" class="btn-upload">
                                <i class="fas fa-camera"></i>
                                <input type="file" id="file" name="imagem" accept="image/*" onclick="sucesso()">
                            </label>
                        </div>
                        <h1 class="mt-3">Perfil de Usuário</h1>
                    </div>
                    <div class="mb-3">
                        <label for="user" class="form-label">Usuário</label>
                        <input type="text" class="form-control" id="user" name="user" value="<?php echo htmlspecialchars($user); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="pass" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="pass" name="pass" placeholder="Nova Senha (deixe em branco para manter a atual)">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary me-2">Salvar</button>
                        <a href="/EPI0/logout.php" class="btn btn-danger">Sair</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <p>© 2024 Denilton Ngale - SH4RKO</p>
</footer>
<script>
    function sucesso(){
        alert("Foto de perfil atualizada com sucesso");
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-xLhXg5DEEptOTI26DDwMeW3A7YF+ZBbDxfMzFq4z4geR/6eQDAxG/gy/6QdB8oeb" crossorigin="anonymous"></script>
</body>
</html>
