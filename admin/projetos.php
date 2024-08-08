calcular o progresso quanto ao estado das tarefas com o mesmo projeto
<?php
include("conexao.php");
session_start();


if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['id'];


$stmt = $conn->prepare("SELECT imagem FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $imagem = $row['imagem'] ?? 'user.png'; 
} else {
    $_SESSION['error_message'] = "Erro ao carregar informações do perfil.";
    header("Location: error.php"); 
    exit();
}


$table_name = "usuario_{$user_id}_tarefas";
$stmt = $conn->prepare("SELECT projeto FROM {$table_name} WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$projects_result = $stmt->get_result();

if ($projects_result === false) {
    $_SESSION['error_message'] = "Erro ao carregar projetos.";
    header("Location: error.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projetos</title>
    <link href="S4 LOGO.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
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
            <img src="S4 LOGO.png" alt="" width="50" height="50">
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
                    <a class="nav-link" href="projetos.php">Projetos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="QA.php">Q&A</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" aria-disabled="true">SH4RKO</a>
                </li>
            </ul>
            <a href="perfil.php" class="header">
                <img src="<?php echo htmlspecialchars('./img/' . $imagem); ?>" id="photo1" class="img-fluid user-image" alt="User Image">
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="text-center">Seus projetos</h1>
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Projeto</th>
                        <th>Progresso</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $projects_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['projeto']); ?></td>
                        
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-XXXXX" crossorigin="anonymous"></script>
</body>
</html>
