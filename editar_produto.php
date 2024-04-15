<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style6.css">
<?php
include('Config.php');

$id_produto = null;

// Verifica se há um ID de produto na URL
if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];

    // Recupere os detalhes do produto para preencher o formulário
    $stmt_produto = $conn->prepare("SELECT * FROM items WHERE id = ?");
    $stmt_produto->bind_param("i", $id_produto);
    $stmt_produto->execute();
    $result_produto = $stmt_produto->get_result();

    if ($result_produto->num_rows === 0) {
        die("Produto não encontrado.");
    }

    $produto = $result_produto->fetch_assoc();
    $stmt_produto->close();
}

// Processar o formulário de edição quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novo_titulo = $_POST['titulo'];
    $nova_descricao = $_POST['descricao'];
    $nova_regiao = isset($_POST['regiao']) ? $_POST['regiao'] : null;
    $nova_condicao = $_POST['condicao'];
    $novo_tipo = $_POST['tipo'];
    $nova_categoria = $_POST['categoria'];

    // Obter o ID da Região
    $stmt_regiao = $conn->prepare("SELECT id_região FROM região WHERE nome_reg = ?");
    $stmt_regiao->bind_param("s", $nova_regiao);
    $stmt_regiao->execute();
    $result_regiao = $stmt_regiao->get_result();

    $id_nova_regiao = null;
    if ($result_regiao->num_rows > 0) {
        $row_regiao = $result_regiao->fetch_assoc();
        $id_nova_regiao = $row_regiao['id_região'];
    }

    $stmt_regiao->close();

    // Atualizar os detalhes do produto no banco de dados
    $stmt_atualizar = $conn->prepare("UPDATE items SET titulo = ?, descricao = ?, id_regiao = ?, id_cond = ?, Tipo_trad = ?, id_categ = ? WHERE id = ?");
    $stmt_atualizar->bind_param("sssissi", $novo_titulo, $nova_descricao, $id_nova_regiao, $nova_condicao, $novo_tipo, $nova_categoria, $id_produto);

    if ($stmt_atualizar->execute()) {
        echo "Produto atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o produto: " . $stmt_atualizar->error;
    }

    $stmt_atualizar->close();
}
?>
<body>
    <h2>Editar Produto</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $id_produto; ?>" enctype="multipart/form-data">
        <!-- Adicione campos do formulário e preencha com os valores atuais do produto -->
        <input type="hidden" name="id_produto" value="<?php echo $id_produto; ?>">
        Título: <input class="titulo" type="text" name="titulo" value="<?php echo $produto['titulo']; ?>" required>
        Região:
        <select class="regiao" name="regiao" required>
            <?php
            $stmt_regiao = $conn->prepare("SELECT * FROM região");
            $stmt_regiao->execute();
            $result_regiao = $stmt_regiao->get_result();

            while ($row_regiao = $result_regiao->fetch_assoc()) {
                $selected = ($row_regiao['id_região'] == $produto['id_regiao']) ? 'selected' : '';
                echo "<option value='" . $row_regiao['nome_reg'] . "' $selected>" . $row_regiao['nome_reg'] . "</option>";
            }

            $stmt_regiao->close();
            ?>
        </select>
        Descrição: <input class="descricao" type="text" name="descricao" value="<?php echo $produto['descricao']; ?>" required>
        Condição:
        <select class="condicao" name="condicao" required>
            <option value="mal" <?php echo ($produto['id_cond'] == 'mal') ? 'selected' : ''; ?>>mal</option>
            <option value="suficiente" <?php echo ($produto['id_cond'] == 'suficiente') ? 'selected' : ''; ?>>suficiente</option>
            <option value="bom" <?php echo ($produto['id_cond'] == 'bom') ? 'selected' : ''; ?>>bom</option>
        </select>
        Tipo:
        <select class="tipo" name="tipo" required>
            <option value="doacao" <?php echo ($produto['Tipo_trad'] == 'doacao') ? 'selected' : ''; ?>>Doação</option>
            <option value="troca" <?php echo ($produto['Tipo_trad'] == 'troca') ? 'selected' : ''; ?>>Troca</option>
        </select>
        Categoria:
        <select class="categoria" name="categoria" required>
            <?php
            $stmt_categorias = $conn->prepare("SELECT * FROM categoria");
            $stmt_categorias->execute();
            $result_categorias = $stmt_categorias->get_result();

            while ($row_categoria = $result_categorias->fetch_assoc()) {
                $selected = ($row_categoria['id_categoria'] == $produto['id_categ']) ? 'selected' : '';
                echo "<option  value='" . $row_categoria['nome'] . "' $selected>" . $row_categoria['nome'] . "</option>";
            }

            $stmt_categorias->close();
            ?>
        </select>


        <input class="submit" type="submit" value="Salvar Alterações">
    </form>
</body>
</html>
