<?php
session_start();
include("conexao.php");

header('Content-Type: application/json'); 

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    $user = htmlspecialchars($user);
    $pass = htmlspecialchars($pass);

    $sql = "SELECT id, user, tipo FROM usuarios WHERE user = ? AND pass = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $response['error'] = 'Erro na preparação da consulta: ' . $conn->error;
        echo json_encode($response);
        exit;
    }

    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['user'] = $usuario['user'];
        $_SESSION['tipo'] = $usuario['tipo'];
        $stmt->close();

        if ($usuario['tipo'] == 'Corporativa') {
            $response['redirect'] = './admin/index.php';
        } else {
            $response['redirect'] = 'index.php';
        }
    } else {
        $response['error'] = "Falha ao logar! E-mail ou senha incorretos";
    }

    echo json_encode($response);
}
?>
