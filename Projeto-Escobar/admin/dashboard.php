<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="sidebar">
        <h2>Administração</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="produtos.php">Produtos</a></li>
            <li><a href="marca.php">Marcas</a></li> <!-- Novo item de menu -->
            <li><a href="categorias.php">Categorias</a></li>
            <li><a href="clientes.php">Clientes</a></li>
            <li><a href="pedidos.php">Pedidos</a></li>
            <li><a href="usuarios.php">Usuários</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <h2>Bem-vindo ao Painel Administrativo</h2>
        <p>Escolha uma opção no menu à esquerda.</p>
    </div>
</body>
</html>
