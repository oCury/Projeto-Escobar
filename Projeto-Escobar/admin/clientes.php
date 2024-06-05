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
            $telefone = $_POST['telefone'];
            $sql = "INSERT INTO clientes (nome, email, telefone) VALUES ('$nome', '$email', '$telefone')";
            $conn->query($sql);
            $message = "Cliente adicionado com sucesso!";
        } elseif (isset($_POST['update'])) {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $sql = "UPDATE clientes SET nome='$nome', email='$email', telefone='$telefone' WHERE id='$id'";
            $conn->query($sql);
            $message = "Cliente atualizado com sucesso!";
        } elseif (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $sql = "DELETE FROM clientes WHERE id='$id'";
            $conn->query($sql);
            $message = "Cliente deletado com sucesso!";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Código de erro para duplicidade
            $message = "Erro: Cliente já existe!";
        } else {
            $message = "Erro: " . $e->getMessage();
        }
    }
}

$clientes = $conn->query("SELECT * FROM clientes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
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
        <h2>Clientes</h2>
        <?php if ($message) echo "<script>alert('$message');</script>"; ?>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <input type="text" name="nome" id="nome" placeholder="Nome do Cliente" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="text" name="telefone" id="telefone" placeholder="Telefone" required>
            <button type="submit" name="add">Adicionar Cliente</button>
            <button type="submit" name="update">Atualizar Cliente</button>
        </form>
        <h3>Lista de Clientes</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $clientes->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['telefone']; ?></td>
                <td>
                    <button type="button" onclick="editCliente(<?php echo $row['id']; ?>, '<?php echo $row['nome']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['telefone']; ?>')">Editar</button>
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
