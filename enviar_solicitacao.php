<?php
include('Config.php');
session_start();

// Verifica se os dados necessários estão presentes na URL
if (isset($_GET['item_oferecido_id'], $_GET['item_desejado_id'], $_GET['id_usuario'], $_GET['user_id'])) {
    $item_oferecido_id = $_GET['item_oferecido_id'];
    $item_desejado_id = $_GET['item_desejado_id'];
    $id_usuario = $_GET['id_usuario'];
    $user_id = $_GET['user_id'];

    // Recupera o tipo de troca da URL
    $tipo_troca = $_GET['tipo_troca'] ?? null;

    // Verifica se o tipo de troca é válido
    if ($tipo_troca !== null && ($tipo_troca === 'doacao' || $tipo_troca === 'troca')) {
        // Verifica se os IDs são numéricos antes de continuar
        if (is_numeric($item_oferecido_id) && is_numeric($item_desejado_id) && is_numeric($id_usuario) && is_numeric($user_id)) {
            // Verifica se já existe uma solicitação pendente para o mesmo item
            $sql_verificar_solicitacao = "SELECT id FROM trades WHERE (item_oferecido_id = ? OR item_desejado_id = ?) AND aceitante_id = ? AND estado = 'p'";
            $stmt_verificar_solicitacao = $conn->prepare($sql_verificar_solicitacao);
            $stmt_verificar_solicitacao->bind_param("iii", $item_oferecido_id, $item_desejado_id, $user_id);
            $stmt_verificar_solicitacao->execute();
            $result_verificar_solicitacao = $stmt_verificar_solicitacao->get_result();

            if ($result_verificar_solicitacao->num_rows > 0) {
                // Já existe uma solicitação pendente para o mesmo item
                echo "Já existe uma solicitação pendente para este item.";

                header('location: indice_atual.php');
                exit;
            }

            // Continua com a inserção normal na tabela trades
            $estado = 'p'; // 'p' para preservado
            $sql = ($tipo_troca == 'doacao') ? "INSERT INTO trades (item_oferecido_id, proponente_id, aceitante_id, estado, Tipo_trad) VALUES (?, ?, ?, ?, ?)" :
                                               "INSERT INTO trades (item_desejado_id, proponente_id, aceitante_id, estado, Tipo_trad) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiiss", $item_oferecido_id, $user_id, $id_usuario, $estado, $tipo_troca);

            if ($stmt->execute()) {
                echo "Solicitação de produto realizada com sucesso!";

                header('location: indice_atual.php');
                exit;
            } else {
                echo "Erro ao realizar a solicitação: " . $stmt->error;

                header('location: indice_atual.php');
            }

            $stmt->close();
        } else {
            echo "IDs não numéricos encontrados na URL.";
        }
    } else {
        echo "Tipo de troca inválido.";
    }
} else {
    echo "IDs não encontrados na URL.";
}

// Feche a conexão com o banco de dados
$conn->close();
?>
