<?php
//conexao.php
$conn = new mysqli('localhost', 'root', '', 'tarefas');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
