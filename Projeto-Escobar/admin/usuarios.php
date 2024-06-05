<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['permissao'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
include '../includes/config.php';

$message = ''; // Inicializa a mensagem

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['add'])) {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT); // Hash da senha
            $permissao = $_POST['permissao'];
            $sql = "INSERT INTO usuarios (nome, email, senha, permissao) VALUES ('$nome', '$email', '$senha', '$permissao')";
            $conn->query($sql);
            $message = "Usuário adicionado com sucesso!";
        } elseif (isset($_POST['update'])) {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $permissao = $_POST['permissao'];
            if (!empty($_POST['senha'])) {
                $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
                $sql = "UPDATE usuarios SET nome='$nome', email='$email', senha='$senha', permissao='$permissao' WHERE id='$id'";
            } else {
                $sql = "UPDATE usuarios SET nome='$nome', email='$email', permissao='$permissao' WHERE id='$id'";
            }
            $conn->query($sql);
            $message = "Usuário atualizado com sucesso!";
        } elseif (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $sql = "DELETE FROM usuarios WHERE id='$id'";
            $conn->query($sql);
            $message = "Usuário deletado com sucesso!";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Código de erro para duplicidade
            $message = "Erro: Usuário já existe!";
        } else {
            $message = "Erro: " . $e->getMessage();
        }
    }
}

$usuarios = $conn->query("SELECT * FROM usuarios");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Usuários</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="sidebar">
        <h2>Administração</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="produtos.php">Produtos</a></li>
            <li><a href="marca.php">Marcas</a></li>
            <li><a href="categorias.php">Categorias</a></li>
            <li><a href="clientes.php">Clientes</a></li>
            <li><a href="pedidos.php">Pedidos</a></li>
            <li><a href="usuarios.php">Usuários</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <h2>Usuários</h2>
        <?php if ($message) echo "<script>alert('$message');</script>"; ?>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <input type="text" name="nome" id="nome" placeholder="Nome do Usuário" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="password" name="senha" id="senha" placeholder="Senha">
            <select name="permissao" id="permissao" required>
                <option value="user">Usuário</option>
                <option value="admin">Administrador</option>
            </select>
            <button type="submit" name="add">Adicionar Usuário</button>
            <button type="submit" name="update">Atualizar Usuário</button>
        </form>
        <h3>Lista de Usuários</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Permissão</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $usuarios->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['permissao']; ?></td>
                <td>
                    <button type="button" onclick="editUsuario(<?php echo $row['id']; ?>, '<?php echo $row['nome']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['permissao']; ?>')">Editar</button>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete">Deletar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>
