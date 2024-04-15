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
            echo 'Codição: ' . $row['condicao_descricao'];
            echo '<br>';
            echo 'Descrição: ' . $row['descricao'];
            echo '</br></br>';

// Função para verificar se um item está disponível na tabela trades
function isItemAvailable($offeredItemId, $desiredItemId)
{
    global $conn;

    $sql = "SELECT estado FROM trades WHERE item_oferecido_id = ? OR item_desejado_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("ii", $offeredItemId, $desiredItemId);
    $stmt->execute();

    $result = $stmt->get_result();

    // Se encontrar resultados na tabela trades, verifica se ambos os itens têm estado 'a'
    if ($result !== false && $result->num_rows > 0) {
        $itemStates = [];
        while ($row = $result->fetch_assoc()) {
            $itemStates[] = $row['estado'];
        }

        return count(array_unique($itemStates)) === 1 && $itemStates[0] === 'a';
    }

    return false;
}

echo '<div class="prod1">';
echo "<button id='btnDoacao' style='display: none;' onclick=\"redirecionar('doacao')\">Doação</button>";
echo "<button id='btnTroca' style='display: none;' onclick=\"redirecionar('troca')\">Troca</button>";

// Verifica se 'user_id' está presente na URL e é igual a 'id_usuario'
if (isset($_GET['user_id']) && $_GET['user_id'] == $id_usuario) {
    $user_id = $_GET['user_id'];
    echo '<input type="submit" value="Excluir Produto" name="submit" onclick="excluirProduto(' . $id . ')">';
    // Adicionar botão de editar produto
    echo '<input type="button" value="Editar Produto" onclick="editarProduto(' . $id . ')">';
} else {

// Verifica se o item oferecido e o item desejado estão disponíveis na tabela trades
if (isItemAvailable($id, $row['id'])) {
    echo 'Produto Indisponível';
} else {
    // Exibe os botões "Doação" e "Troca" apenas se o estado não for 'a'
    echo "<script>
            document.getElementById('btnDoacao').style.display = 'inline-block';
            document.getElementById('btnTroca').style.display = 'inline-block';
        </script>";

    // Verifica se o item existe na tabela trades e o estado é 'a'
    $sqlCheckTrade = "SELECT * FROM trades WHERE (item_oferecido_id = ? AND item_desejado_id = ?) OR (item_oferecido_id = ? AND item_desejado_id = ?) AND estado = 'a'";
    $stmtCheckTrade = $conn->prepare($sqlCheckTrade);

    if ($stmtCheckTrade !== false) {
        $stmtCheckTrade->bind_param("iiii", $id, $row['id'], $row['id'], $id);
        $stmtCheckTrade->execute();

        $resultCheckTrade = $stmtCheckTrade->get_result();

        if ($resultCheckTrade !== false && $resultCheckTrade->num_rows > 0) {
            echo 'Produto Indisponível'; // Se o item existe na tabela trades com estado 'a'
        } else {
            // Exibe os botões "Doação" e "Troca" apenas se o estado não for 'a'
            echo "<script>
                    document.getElementById('btnDoacao').style.display = 'inline-block';
                    document.getElementById('btnTroca').style.display = 'inline-block';
                </script>";
        }

        $stmtCheckTrade->close();
    } else {
        echo "Erro na preparação da consulta: " . $conn->error;
    }

    echo '<button onclick="acessarPerfil(' . $id_usuario . ')">Ver perfil do usuário que adicionou o produto</button>';
    echo "<button onclick=\"window.location.href='messages.php?recipient_id=" . $id_usuario . "&ID=" . $user_id . "';\">Enviar Mensagem</button>";
}

            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
    } else {
        // Se nenhum produto for encontrado, exibe uma mensagem correspondente
        echo "Nenhum produto encontrado.";
    }

    $stmt->close();
} else {
    echo "ID do produto ou ID do usuário não especificado na URL";
}

?>
<script>
// Funções para interação com produtos
function acessarPerfil(userId) {
    window.location.href = 'Perfil.php?ID=' + userId;
}

function editarProduto(id){
    if (confirm("Deseja editar o produto ?")){
        window.location.href = "editar_produto.php?id=" + id;
    }
}

function excluirProduto(id) {
    if (confirm("Tem certeza de que deseja excluir este produto?")) {
        window.location.href = "delete_produto.php?id=" + id;
    }
}

// Função para redirecionar com base na escolha do usuário
function redirecionar(tipoTroca) {
    if (tipoTroca === 'doacao' || tipoTroca === 'troca') {
        // Adiciona alertas para informar a escolha do usuário
        if (tipoTroca === 'doacao') {
            alert("Você escolheu 'Doação'. Espere até que a solicitação seja aceite.");
        } else {
            alert("Você escolheu 'Troca'. Será redirecionado para a página de seleção de itens.");
        }

        // Redireciona com base na escolha do usuário
        if (tipoTroca === 'troca') {
            window.location.href = "Select_item.php?item_desejado_id=<?php echo $id ?>&id_usuario=<?php echo $id_usuario?>&user_id=<?php echo $user_id ?>&tipo_troca=" + tipoTroca;
        } else {
            // Se for 'doação', direciona para a página de solicitação
            window.location.href = "enviar_solicitacao.php?item_desejado_id=<?php echo $id ?>&item_oferecido_id=<?php echo $id ?>&id_usuario=<?php echo $id_usuario ?>&user_id=<?php echo $user_id ?>&tipo_troca=" + tipoTroca;
        }
    } else {
        alert("Tipo de troca inválido.");
    }
}
</script>
<?php 
// Feche a conexão com o banco de dados
$conn->close();
?>