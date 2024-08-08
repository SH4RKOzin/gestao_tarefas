<?php
$conn = new mysqli('localhost:8223', 'root', '', 'tarefas');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
