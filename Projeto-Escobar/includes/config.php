<?php
$servername = "localhost";
$username = "root";
$password = "123";
$dbname = "lojaprojeto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
