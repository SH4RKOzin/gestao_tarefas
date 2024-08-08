<?php
session_start();
include("conexao.php");


if (!isset($_SESSION['id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $tarefa_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM usuario_{$user_id}_tarefas WHERE id = ?");
    $stmt->bind_param("i", $tarefa_id);

  
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Tarefa removida com sucesso.";
    } else {
        $_SESSION['error_message'] = "Erro ao remover tarefa.";
    }

   
    header('Location: index.php');
    exit;
} else {

    $_SESSION['error_message'] = "Requisição inválida para remoção de tarefa.";
    header('Location: index.php');
    exit;
}
?>