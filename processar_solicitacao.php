<?php
include('Config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trade_id = $_POST['trade_id'];

    if (isset($_POST['aceitar']) && $_POST['aceitar'] === '1') {
        // Lógica para aceitar a solicitação
        var_dump($_POST);

        $sql_obter_ids = "SELECT item_oferecido_id, item_desejado_id FROM trades WHERE id = ?";
        $stmt_obter_ids = $conn->prepare($sql_obter_ids);

        if ($stmt_obter_ids) {
            $stmt_obter_ids->bind_param("i", $trade_id);
            $stmt_obter_ids->execute();
            $stmt_obter_ids->bind_result($item_oferecido_id, $item_desejado_id);

            if ($stmt_obter_ids->fetch()) {

                // Feche os resultados da consulta anterior
                $stmt_obter_ids->close();

                // Atualizar o estado da trade para 'aceita'
                $novo_estado = 'aceita';
                $update_trade_sql = "UPDATE trades SET estado = ? WHERE id = ?";
                $stmt_update_trade = $conn->prepare($update_trade_sql);

                            // Redirecionar para a página principal após recusar
            header('Location: Indice_Atual.php');

                if ($stmt_update_trade) {
                    $stmt_update_trade->bind_param("si", $novo_estado, $trade_id);
                    $stmt_update_trade->execute();
                    $stmt_update_trade->close();

                    exit;
                } else {
                    die('Erro ao preparar a atualização do estado da trade: ' . $conn->error);
                }
            } else {
                echo 'Não foi possível obter os IDs do item.';
            }
        } else {
            die('Erro ao preparar a consulta para obter IDs: ' . $conn->error);
        }
    } elseif (isset($_POST['recusar'])) {
        // Lógica para recusar a solicitação

        // Excluir a entrada correspondente na tabela trades
        $sqlExcluir = "DELETE FROM trades WHERE id = ?";
        $stmtExcluir = $conn->prepare($sqlExcluir);

        if ($stmtExcluir) {
            $stmtExcluir->bind_param("i", $trade_id);
            $stmtExcluir->execute();
            $stmtExcluir->close();

            // Redirecionar para a página principal após recusar
            header('Location: Indice_Atual.php');
            exit;
        } else {
            die('Erro ao preparar a exclusão da solicitação: ' . $conn->error);
        }
    }
}
?>
