<?php
include("conexao.php");
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['id'];
$user = $_SESSION['user'];
// Consulta os dados do usuário
$stmt = $conn->prepare("SELECT imagem FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $imagem = $row['imagem']; // Assume que o nome do arquivo da imagem está armazenado no banco de dados
} else {
    $_SESSION['error_message'] = "Erro ao carregar informações do perfil.";
    exit();
}
// Inicializa a variável $result
$result = null;

if (!empty($_GET['search'])) {
    $search_term = "%{$_GET['search']}%";
    $stmt = $conn->prepare("SELECT * FROM usuario_{$user_id}_tarefas WHERE estado LIKE ? OR tarefa LIKE ?");
    $stmt->bind_param("ss", $search_term, $search_term); // Corrigido para dois "s", pois temos dois placeholders
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM usuario_{$user_id}_tarefas");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link href="S4 LOGO.png" rel="icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
       body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .footer {
            width: 100%;
            background-color: #343a40;;
            text-align: center;

            position: absolute;
            bottom: 0;
        }
        .footer p{
          color: white;
        }
        #photo {
            max-width: 40px;
            max-height: 40px;
            width: auto;
            height: auto;
        }
        a{
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
                    <a class="nav-link" href="projetos.php">Projetos</a>
                </li>
        <li class="nav-item">
          <a class="nav-link" href="QA.php">Q&A</a>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled" aria-disabled="true">SH4RKO</a>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="Pesquisar" id="pesquisar"> <!-- Corrigido para id="pesquisar" -->
        <button class="btn btn-outline-success" type="button" onclick="searchData()">Pesquisar</button> <!-- Corrigido para type="button" -->
      </form>
      <a href="perfil.php">
                <img src="<?php echo htmlspecialchars($imagem ? './img/' . htmlspecialchars($imagem) : 'user.png'); ?>" id="photo" class="img-fluid user-image" alt="User Image">
            </a>
    </div>
  </div>
</nav>

<script>
    var pesquisar = document.getElementById("pesquisar"); // Corrigido para pesquisar
    pesquisar.addEventListener("keydown", function(event) { // Corrigido para pesquisar
        if (event.key === "Enter") {
            searchData();
        }
    });

    function searchData() {
        window.location = 'index.php?search=' + pesquisar.value; // Corrigido para pesquisar
    }
</script>

<div class="container mt-4">
    <h1 class="text-center">Bem-vindo, <?php echo htmlspecialchars($user); ?>!</h1>
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tarefa</th>
                        <th>Estado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['tarefa']); ?></td>
                            <td><?php echo htmlspecialchars($row['estado']); ?></td>
                            <td>
                                <a href="editar_tarefa.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
</svg></a>
                                <a href="removerTarefa.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
</svg></a>
                          <a href="detalhes.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
  <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>
</svg></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <p class="text-center"><a href="criar_tarefa.php" class="btn btn-success"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
</svg></a></p>
        </div>
    </div>
</div>
<footer class="footer">
    <p>© 2024 Denilton Ngale - SH4RKO</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-9anC3EepQ3LKW0zQf5L5Ive2f0WmE9zuXFvrDhUto29T95rQD+/ZvsKbF0JDLiXt" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-1Y3PqNigMqN4DKP8SivXQODV5OPoA1pM9Y2OoDq9bsA4DlOi+CIyTE5lMzyfc6+C" crossorigin="anonymous"></script>
</body>
</html>
