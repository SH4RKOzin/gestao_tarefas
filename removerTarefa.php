<?php
session_start();
include("conexao.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Verifica se o método da requisição é GET e se o parâmetro 'id' está presente
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $tarefa_id = $_GET['id'];

    // Prepara a declaração para deletar a tarefa específica do usuário
    $stmt = $conn->prepare("DELETE FROM usuario_{$user_id}_tarefas WHERE id = ?");
    $stmt->bind_param("i", $tarefa_id);

    // Executa a declaração preparada
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Tarefa removida com sucesso.";
    } else {
        $_SESSION['error_message'] = "Erro ao remover tarefa.";
    }

    // Redireciona de volta para a página inicial após a operação
    header('Location: index.php');
    exit;

    $stmt->close();
} else {
    // Caso a requisição seja inválida
    $_SESSION['error_message'] = "Requisição inválida para remoção de tarefa.";
    header('Location: index.php');
    exit;
}
?>