<?php
session_start();
if (isset($_SESSION['loggedin'])) {
    header("Location: admin/dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'includes/config.php';

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($senha, $row['senha'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['nome'] = $row['nome'];
            $_SESSION['permissao'] = $row['permissao'];
            header("Location: admin/dashboard.php");
            exit;
        } else {
            $error = "Senha incorreta!";
        }
    } else {
        $error = "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success') { ?>
            <script>alert('Logout realizado com sucesso!');</script>
        <?php } ?>
        <form method="POST" action="">
            <h2>Login</h2>
            <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
