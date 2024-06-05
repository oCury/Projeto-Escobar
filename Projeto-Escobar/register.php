<?php
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); // Hash da senha
    $permissao = $_POST['permissao'];

    $sql = "INSERT INTO usuarios (nome, email, senha, permissao) VALUES ('$nome', '$email', '$senha', '$permissao')";
    if ($conn->query($sql) === TRUE) {
        echo "Usuário registrado com sucesso!";
    } else {
        echo "Erro: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuário</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="POST" action="">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <select name="permissao">
            <option value="user">Usuário</option>
            <option value="admin">Administrador</option>
        </select>
        <button type="submit">Registrar</button>
    </form>
</body>
</html>
