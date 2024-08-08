<?php
session_start();
include("conexao.php");


if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tarefa = $_POST['tarefa'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];
    $estado = $_POST['estado'];
    $alocacao = $_POST['alocacao'];
    $descricao = $_POST['descricao'];
    $projeto = $_POST['projeto'];

    if ($stmt = $conn->prepare("INSERT INTO usuario_{$user_id}_tarefas (tarefa, data_inicio, data_fim, estado, alocacao, descricao, projeto) VALUES (?, ?, ?, ?, ?, ?, ?)")) {
        $stmt->bind_param("sssssss", $tarefa, $data_inicio, $data_fim, $estado, $alocacao, $descricao, $projeto);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Tarefa criada com sucesso.";
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error_message'] = "Erro ao criar tarefa.";
            header('Location: criar_tarefa.php');
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Erro ao preparar a consulta.";
        header('Location: criar_tarefa.php');
        exit;
    }
}

if ($stmt = $conn->prepare("SELECT imagem FROM usuarios WHERE id = ?")) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $imagem = $row['imagem'];
    } else {
        $_SESSION['error_message'] = "Erro ao carregar informações do perfil.";
        exit();
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Tarefa</title>
    <link href="S4 LOGO.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
            max-width: 40px;
            max-height: 40px;
            width: auto;
            height: auto;
        }
        a {
            text-decoration: none;
            text-transform: none;
            color: #fff;
            margin-right: 5px;
            margin-left: 15px;
        }
        .error-message {
            font-size: 0.875em;
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
      <a href="perfil.php">
        <img src="<?php echo htmlspecialchars($imagem ? './img/' . htmlspecialchars($imagem) : 'user.png'); ?>" id="photo" class="img-fluid user-image" alt="User Image">
      </a>
    </div>
  </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="criar_tarefa.php" method="POST" class="p-4 border shadow-lg rounded mt-4">
                <div class="text-center mb-4">
                    <img src="S4 LOGO.png" alt="S4 Logo" width="200" height="200">
                    <h1 class="mt-3">Criar Tarefa</h1>
                </div>
                <div class="mb-3">
                    <label for="tarefa" class="form-label">Tarefa</label>
                    <input type="text" class="form-control" name="tarefa" id="tarefa" >
                </div>
                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <input type="text" class="form-control" name="descricao" id="descricao" >
                </div>
                <div class="mb-3">
                    <label for="projeto" class="form-label">Projeto</label>
                    <input type="text" class="form-control" name="projeto" id="projeto">
                </div>
                <div class="mb-3">
                    <label for="data_inicio" class="form-label">Data de Início</label>
                    <input type="date" class="form-control" name="data_inicio" id="data_inicio" >
                </div>
                <div class="mb-3">
                    <label for="data_fim" class="form-label">Data de Fim</label>
                    <input type="date" class="form-control" name="data_fim" id="data_fim" >
                </div>
                <div class="mb-3">
                    <label for="alocacao" class="form-label">Alocado a</label>
                    <input type="text" class="form-control" name="alocacao" id="alocacao" >
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-control" name="estado" id="estado" >
                        <option value="">Selecione o estado</option>
                        <option value="Nao iniciada">Não iniciada</option>
                        <option value="Em andamento">Em andamento</option>
                        <option value="Concluída">Concluída</option>
                    </select>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy" viewBox="0 0 16 16">
                            <path d="M11 2H9v3h2z"/>
                            <path d="M1.5 0h11.586a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 14.5v-13A1.5 1.5 0 0 1 1.5 0M1 1.5v13a.5.5 0 0 0 .5.5H2v-4.5A1.5 1.5 0 0 1 3.5 9h9a1.5 1.5 0 0 1 1.5 1.5V15h.5a.5.5 0 0 0 .5-.5V2.914a.5.5 0 0 0-.146-.353l-1.415-1.415A.5.5 0 0 0 13.086 1H13v4.5A1.5 1.5 0 0 1 11.5 7h-7A1.5 1.5 0 0 1 3 5.5V1H1.5a.5.5 0 0 0-.5.5m3 4a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V1H4zM3 15h10v-4.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5z"/>
                        </svg>
                        Salvar
                    </button>
                    <a href="index.php" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                        </svg>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('form').submit(function(event) {
        $('.error-message').remove();
        let isValid = true;
        if ($('#tarefa').val().trim() === '') {
            $('#tarefa').after('<div class="error-message text-danger">O campo tarefa é obrigatório.</div>');
            isValid = false;
        }
        if ($('#descricao').val().trim() === '') {
            $('#descricao').after('<div class="error-message text-danger">O campo descrição é obrigatório.</div>');
            isValid = false;
        }
        if ($('#data_inicio').val() === '') {
            $('#data_inicio').after('<div class="error-message text-danger">O campo data de início é obrigatório.</div>');
            isValid = false;
        }
        if ($('#data_fim').val() === '') {
            $('#data_fim').after('<div class="error-message text-danger">O campo data de fim é obrigatório.</div>');
            isValid = false;
        }
        if ($('#alocacao').val().trim() === '') {
            $('#alocacao').after('<div class="error-message text-danger">O campo alocação é obrigatório.</div>');
            isValid = false;
        }
        if ($('#estado').val() === '') {
            $('#estado').after('<div class="error-message text-danger">O campo estado é obrigatório.</div>');
            isValid = false;
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
});
</script>
</body>
</html>