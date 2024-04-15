<?php
include('Config.php');
session_start();

ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo 'Usuário não está logado.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trade_id = $_POST['trade_id'];

   // Excluir os dois itens da tabela 'items'
$delete_item_sql = "DELETE FROM items WHERE id IN (?, ?)";
$stmt_delete_item = $conn->prepare($delete_item_sql);

if ($stmt_delete_item) {
    // Substitua $item_oferecido_id e $item_desejado_id pelos IDs reais dos itens
    $stmt_delete_item->bind_param("ii", $item_oferecido_id, $item_desejado_id);
    $stmt_delete_item->execute();
    $stmt_delete_item->close();

    // Atualizar o estado e armazenar os IDs dos itens na tabela 'trades'
    $novo_estado = 'aceita';
    $update_trade_sql = "UPDATE trades SET estado = ?, item_oferecido_id = ?, item_desejado_id = ? WHERE id = ?";
    $stmt_update_trade = $conn->prepare($update_trade_sql);

    if ($stmt_update_trade) {
        $stmt_update_trade->bind_param("siii", $novo_estado, $item_oferecido_id, $item_desejado_id, $trade_id);
        $stmt_update_trade->execute();
        $stmt_update_trade->close();

        // Redirecionar de volta para a página de solicitações ou qualquer página desejada
        header('Location: Solicitacoes.php');
        exit;
    } else {
        die('Erro ao preparar a atualização do estado da trade: ' . $conn->error);
    }
} else {
    die('Erro ao preparar a exclusão dos itens: ' . $conn->error);
}
}
?>
