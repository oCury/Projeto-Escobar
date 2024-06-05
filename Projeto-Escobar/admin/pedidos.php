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
            $cliente_id = $_POST['cliente_id'];
            $produto_id = $_POST['produto_id'];
            $quantidade = $_POST['quantidade'];
            $sql = "INSERT INTO pedidos (cliente_id, produto_id, quantidade) VALUES ('$cliente_id', '$produto_id', '$quantidade')";
            $conn->query($sql);
            $message = "Pedido adicionado com sucesso!";
        } elseif (isset($_POST['update'])) {
            $id = $_POST['id'];
            $cliente_id = $_POST['cliente_id'];
            $produto_id = $_POST['produto_id'];
            $quantidade = $_POST['quantidade'];
            $sql = "UPDATE pedidos SET cliente_id='$cliente_id', produto_id='$produto_id', quantidade='$quantidade' WHERE id='$id'";
            $conn->query($sql);
            $message = "Pedido atualizado com sucesso!";
        } elseif (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $sql = "DELETE FROM pedidos WHERE id='$id'";
            $conn->query($sql);
            $message = "Pedido deletado com sucesso!";
        }
    } catch (mysqli_sql_exception $e) {
        $message = "Erro: " . $e->getMessage();
    }
}

$pedidos = $conn->query("SELECT pedidos.id, clientes.nome AS cliente, produtos.nome AS produto, pedidos.quantidade 
                        FROM pedidos 
                        JOIN clientes ON pedidos.cliente_id = clientes.id 
                        JOIN produtos ON pedidos.produto_id = produtos.id");
$clientes = $conn->query("SELECT id, nome FROM clientes");
$produtos = $conn->query("SELECT id, nome FROM produtos");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pedidos</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="sidebar">
        <h2>Administração</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="produtos.php">Produtos</a></li>
            <li><a href="categorias.php">Categorias</a></li>
            <li><a href="clientes.php">Clientes</a></li>
            <li><a href="pedidos.php">Pedidos</a></li>
            <li><a href="usuarios.php">Usuários</a></li>
        </ul>
    </div>
    <div class="content">
        <h2>Pedidos</h2>
        <?php if ($message) echo "<script>alert('$message');</script>"; ?>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <select name="cliente_id" id="cliente_id" required>
                <option value="">Selecione o Cliente</option>
                <?php while ($row = $clientes->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
                <?php endwhile; ?>
            </select>
            <select name="produto_id" id="produto_id" required>
                <option value="">Selecione o Produto</option>
                <?php while ($row = $produtos->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="quantidade" id="quantidade" placeholder="Quantidade" required>
            <button type="submit" name="add">Adicionar Pedido</button>
            <button type="submit" name="update">Atualizar Pedido</button>
        </form>
        <h3>Lista de Pedidos</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $pedidos->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['cliente']; ?></td>
                <td><?php echo $row['produto']; ?></td>
                <td><?php echo $row['quantidade']; ?></td>
                <td>
                    <button type="button" onclick="editPedido(<?php echo $row['id']; ?>, '<?php echo $row['cliente_id']; ?>', '<?php echo $row['produto_id']; ?>', '<?php echo $row['quantidade']; ?>')">Editar</button>
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
