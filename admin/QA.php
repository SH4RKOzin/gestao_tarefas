<?php
include("conexao.php");
session_start();

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
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suporte e Ajuda</title>
    <link href="S4 LOGO.png" rel="icon">
    <!-- Link para o Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<style>
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



    <div class="text-center py-3">
        <h1>Suporte e Ajuda</h1>
    </div>

    <div class="container mt-4">
        <section class="faq-section mb-4">
            <h2>Perguntas Frequentes</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Como crio uma nova tarefa?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Para criar uma nova tarefa, clique no botão "+" na pagina index. Preencha os detalhes da tarefa e clique em "Salvar".
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Como edito uma tarefa existente?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Para editar uma tarefa, clique no botao do lapis na tarefa que deseja editar na lista de tarefas. Faça as alterações necessárias e clique em "Salvar".
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            O que faço se esquecer minha senha?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Se você esquecer sua senha, clique no link "Esqueci minha senha" na página de login. Siga as instruções para redefinir sua senha.
                        </div>
                    </div>
                </div>
               <!-- <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            Como visualizo o progresso dos meus projetos?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Você pode visualizar o progresso dos projetos na página de "Projetos". Lá, você verá uma visão geral de todas as tarefas relacionadas a cada projeto e seu status.
                        </div>
                    </div>
                </div> -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            Como atribuo uma tarefa a um membro da equipe?
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Ao criar ou editar uma tarefa, você pode selecionar o membro da equipe responsável na seção "Atribuir a".
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingSix">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            Como posso excluir uma tarefa?
                        </button>
                    </h2>
                    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Para excluir uma tarefa, clique na tarefa que deseja excluir e depois clique no botão "Excluir". Confirme a exclusão quando solicitado.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-section mb-4">
            <h2>Contato</h2>
            <form class="row g-3" action="https://formsubmit.co/deniltonngale55@gmail.com" method="POST">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="col-12">
                    <label for="message" class="form-label">Mensagem</label>
                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Enviar</button>
                </div>
            </form>
        </section>
    </div>

    <!-- Script para o Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
