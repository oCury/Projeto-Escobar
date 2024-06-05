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
            $sql = "INSERT INTO marcas (nome) VALUES ('$nome')";
            $conn->query($sql);
            $message = "Marca adicionada com sucesso!";
        } elseif (isset($_POST['update'])) {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $sql = "UPDATE marcas SET nome='$nome' WHERE id='$id'";
            $conn->query($sql);
            $message = "Marca atualizada com sucesso!";
        } elseif (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $sql = "DELETE FROM marcas WHERE id='$id'";
            $conn->query($sql);
            $message = "Marca deletada com sucesso!";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Código de erro para duplicidade
            $message = "Erro: Marca já existe!";
        } else {
            $message = "Erro: " . $e->getMessage();
        }
    }
}

$marcas = $conn->query("SELECT * FROM marcas");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marcas</title>
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
        <h2>Marcas</h2>
        <?php if ($message) echo "<script>alert('$message');</script>"; ?>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <input type="text" name="nome" id="nome" placeholder="Nome da Marca" required>
            <button type="submit" name="add">Adicionar Marca</button>
            <button type="submit" name="update">Atualizar Marca</button>
        </form>
        <h3>Lista de Marcas</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $marcas->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nome']; ?></td>
                <td>
                    <button type="button" onclick="editMarca(<?php echo $row['id']; ?>, '<?php echo $row['nome']; ?>')">Editar</button>
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
