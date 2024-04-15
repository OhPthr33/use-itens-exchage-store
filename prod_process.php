<!DOCTYPE html>
<html lang="pt">
<head>
    <!-- Metadados e título da página -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/Style4.css">
</head>
</html>
<?php
include('Config.php');
session_start();

$id = $_GET['id'] ?? null;
$id_usuario = $_GET['id_usuario'] ?? null;
$user_id = $_GET['user_id'] ?? null;

// Verifica se os parâmetros 'id' e 'usuario_id' estão definidos na URL
if ($id !== null && $id_usuario !== null) {
    // Consulta para obter os detalhes do produto com base no ID e no ID do usuário
    $stmt = $conn->prepare("SELECT items.*, usuarios.user_type, condição.descrição AS condicao_descricao, categoria.nome AS categoria_nome, região.nome_reg AS região_nome
    FROM items
    INNER JOIN condição ON items.id_cond = condição.id_cond
    INNER JOIN categoria ON items.id_categ = categoria.id_categoria
    INNER JOIN região ON items.id_regiao = região.id_região
    INNER JOIN usuarios ON items.usuario_id = usuarios.ID
    WHERE items.id = ? AND items.usuario_id = ?");

    // Verificar se a preparação da consulta foi bem-sucedida
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("ii", $id, $id_usuario);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Exibe os detalhes do produto, incluindo imagem, título e descrição
            echo '<div class="container21">';
            echo '<div class="produto21">';
            echo '<a href="Itens.php?id=' . $row['id'] . '">' . '</a>';
            echo '<img src="' . $row['imagem'] . '" alt="Produto">';
            echo '<div class="prod">';
            echo '<h1>';
            echo '<h2>' . $row['titulo'] . '</h2>';
            echo '</h1>';
            echo 'Categoria: ' . $row['categoria_nome'];
            echo '<br>';
            echo 'Região: ' . $row['região_nome'];
            echo '<br>';
            echo 'Condição: ' . $row['condicao_descricao'];
            echo '<br>';
            echo 'Descrição: ' . $row['descricao'];
            echo '</br></br>';

            echo '<div class="prod1">';
            // Adiciona os dois botões ("Aceitar" e "Rejeitar")
            echo "<form method='post'>";
            echo "<input type='hidden' name='acao' value='aceitar'>";
            echo "<input type='hidden' name='idItem' value='{$row['id']}'>";
            echo "<input type='hidden' name='idUsuario' value='{$id_usuario}'>";
            echo "<button type='submit'>Aceitar</button>";
            echo "</form>";
            echo "<form method='post'>";
            echo "<input type='hidden' name='acao' value='rejeitar'>";
            echo "<input type='hidden' name='idItem' value='{$row['id']}'>";
            echo "<input type='hidden' name='idUsuario' value='{$id_usuario}'>";
            echo "<button type='submit'>Rejeitar</button>";
            echo "</form>";

            echo '<button onclick="acessarPerfil(' . $id_usuario . ')">Ver perfil do usuário que adicionou o produto</button>';
            echo "<button onclick=\"window.location.href='messages.php?recipient_id=" . $id_usuario . "&ID=" . $user_id . "';\">Enviar Mensagem</button>";

            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        // Se nenhum produto for encontrado, exibe uma mensagem correspondente
        echo "Nenhum produto encontrado.";
    }

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os parâmetros do formulário
    $acao = $_POST['acao'] ?? '';
    $idItem = $_POST['idItem'] ?? '';
    $idUsuario = $_POST['idUsuario'] ?? '';

    if ($acao === 'aceitar') {
        // Atualiza o estado do item para 'D'
        $stmtUpdate = $conn->prepare("UPDATE items SET estado = 'D' WHERE id = ?");
        $stmtUpdate->bind_param("i", $idItem);
        $stmtUpdate->execute();
        $stmtUpdate->close();

        echo '<script>alert("Produto aceito no site."); window.location.href = "admin.php";</script>';
    } elseif ($acao === 'rejeitar') {
        // Exclui o produto da tabela items
        $stmtDelete = $conn->prepare("DELETE FROM items WHERE id = ?");
        $stmtDelete->bind_param("i", $idItem);
        $stmtDelete->execute();
        $stmtDelete->close();

        echo '<script>alert("Produto rejeitado e removido do site."); window.location.href = "admin.php";</script>';
    }
    // Adicione condições para outras ações, se necessário
}

?>
<script>
function acessarPerfil(userId) {
    window.location.href = 'Perfil.php?ID=' + userId;
}
</script>
<?php 
// Feche a conexão com o banco de dados
$conn->close();
?>
