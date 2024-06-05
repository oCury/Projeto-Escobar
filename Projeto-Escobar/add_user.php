<?php
include 'includes/config.php';

$nome = 'Escobar';
$email = 'escobar@ite.com.br';
$senha = password_hash('1234', PASSWORD_BCRYPT); // Hash da senha para segurança
$permissao = 'admin';

// Verificar se o usuário já existe
$sql = "SELECT * FROM usuarios WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Inserir o novo usuário
    $sql = "INSERT INTO usuarios (nome, email, senha, permissao) VALUES ('$nome', '$email', '$senha', '$permissao')";
    if ($conn->query($sql) === TRUE) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário: " . $conn->error;
    }
} else {
    echo "Usuário já existe!";
}
?>
