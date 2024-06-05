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
            $descricao = $_POST['descricao'];
            $preco = $_POST['preco'];
            $categoria_id = $_POST['categoria_id'];
            $marca_id = $_POST['marca_id'];
            $sql = "INSERT INTO produtos (nome, descricao, preco, categoria_id, marca_id) VALUES ('$nome', '$descricao', '$preco', '$categoria_id', '$marca_id')";
            $conn->query($sql);
            $message = "Produto adicionado com sucesso!";
        } elseif (isset($_POST['update'])) {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $descricao = $_POST['descricao'];
            $preco = $_POST['preco'];
            $categoria_id = $_POST['categoria_id'];
            $marca_id = $_POST['marca_id'];
            $sql = "UPDATE produtos SET nome='$nome', descricao='$descricao', preco='$preco', categoria_id='$categoria_id', marca_id='$marca_id' WHERE id='$id'";
            $conn->query($sql);
            $message = "Produto atualizado com sucesso!";
        } elseif (isset($_POST['delete'])) {
            $id = $_POST['id'];
            $sql = "DELETE FROM produtos WHERE id='$id'";
            $conn->query($sql);
            $message = "Produto deletado com sucesso!";
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) { // Código de erro para duplicidade
            $message = "Erro: Produto já existe!";
        } else {
            $message = "Erro: " . $e->getMessage();
        }
    }
}

$produtos = $conn->query("SELECT produtos.id, produtos.nome, produtos.descricao, produtos.preco, categorias.nome AS categoria, marcas.nome AS marca 
                          FROM produtos 
                          JOIN categorias ON produtos.categoria_id = categorias.id
                          JOIN marcas ON produtos.marca_id = marcas.id");
$categorias = $conn->query("SELECT id, nome FROM categorias");
$marcas = $conn->query("SELECT id, nome FROM marcas");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
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
    </ul>
</div>
    <div class="content">
        <h2>Produtos</h2>
        <?php if ($message) echo "<script>alert('$message');</script>"; ?>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <input type="text" name="nome" id="nome" placeholder="Nome do Produto" required>
            <textarea name="descricao" id="descricao" placeholder="Descrição" required></textarea>
            <input type="number" step="0.01" name="preco" id="preco" placeholder="Preço" required>
            <select name="categoria_id" id="categoria_id" required>
                <option value="">Selecione a Categoria</option>
                <?php while ($row = $categorias->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
                <?php endwhile; ?>
            </select>
            <select name="marca_id" id="marca_id" required>
                <option value="">Selecione a Marca</option>
                <?php while ($row = $marcas->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['nome']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="add">Adicionar Produto</button>
            <button type="submit" name="update">Atualizar Produto</button>
        </form>
        <h3>Lista de Produtos</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Categoria</th>
                <th>Marca</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $produtos->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo $row['descricao']; ?></td>
                <td><?php echo $row['preco']; ?></td>
                <td><?php echo $row['categoria']; ?></td>
                <td><?php echo $row['marca']; ?></td>
                <td>
                    <button type="button" onclick="editProduto(<?php echo $row['id']; ?>, '<?php echo $row['nome']; ?>', '<?php echo $row['descricao']; ?>', '<?php echo $row['preco']; ?>', '<?php echo $row['categoria_id']; ?>', '<?php echo $row['marca_id']; ?>')">Editar</button>
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
