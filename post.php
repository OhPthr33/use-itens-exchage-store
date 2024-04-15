<?php
session_start();
include('config.php');

// Verificar se a sessão está ativa e se o ID do usuário está definido
if (isset($_SESSION['ID'])) {
    $userId = $_SESSION['ID'];

    // Verificar se a mensagem WebSocket está sendo recebida corretamente
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if ($data && isset($data['type']) && $data['type'] === 'chat' && isset($data['userId'], $data['message'])) {
        // Prevenção de SQL Injection
        $text = $conn->real_escape_string($data['message']);

        // Definir valores para remetente_id e destinatario_id
        $remetente_id = $userId;
        $destinatario_id = $data['userId'];

        // Utilizar prepared statements para evitar SQL injection
        $stmt = $conn->prepare("INSERT INTO messages (remetente_id, destinatario_id, conteudo, data_hora_envio) VALUES (?, ?, ?, NOW())");

        // Verificar se a preparação da declaração foi bem-sucedida
        if ($stmt) {
            $stmt->bind_param("iis", $remetente_id, $destinatario_id, $text);

            // Verificar se a execução da declaração foi bem-sucedida
            if ($stmt->execute()) {
                echo "Mensagem enviada com sucesso";
                echo '<script>console.log("Mensagem enviada com sucesso");</script>';
            } else {
                echo "Erro ao executar a declaração: " . $stmt->error;
                echo '<script>console.log("Erro ao executar a declaração: ' . $stmt->error . '");</script>';
            }

            $stmt->close();
        } else {
            echo "Erro ao preparar a declaração: " . $conn->error;
            echo '<script>console.log("Erro ao preparar a declaração: ' . $conn->error . '");</script>';
        }
    } else {
        echo "Dados da mensagem WebSocket ausentes ou inválidos.";
        echo '<script>console.log("Dados da mensagem WebSocket ausentes ou inválidos.");</script>';
    }
} else {
    echo "A sessão não está ativa ou o ID do usuário não está definido.";
    echo '<script>console.log("A sessão não está ativa ou o ID do usuário não está definido.");</script>';
}

$conn->close();
?>
