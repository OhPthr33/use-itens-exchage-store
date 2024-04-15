<?php
include('config.php');

// Recupera os parâmetros da URL
$userId = isset($_GET['ID']) ? $_GET['ID'] : null;
$recipientId = isset($_GET['recipient_id']) ? $_GET['recipient_id'] : null;

if ($userId && $recipientId) {
    // Utilize prepared statements para prevenir SQL injection
    $query = "SELECT remetente_id, destinatario_id, conteudo, data_hora_envio FROM messages WHERE ((remetente_id = ? AND destinatario_id = ?) OR (remetente_id = ? AND destinatario_id = ?)) ORDER BY data_hora_envio";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $userId, $recipientId, $recipientId, $userId);
    $stmt->execute();
    $stmt->bind_result($remetente_id, $destinatario_id, $conteudo, $data_hora_envio);

    $messages = array();

    while ($stmt->fetch()) {
        $message_item = array(
            'remetente_id' => $remetente_id,
            'destinatario_id' => $destinatario_id,
            'conteudo' => $conteudo,
            'data_hora_envio' => $data_hora_envio
        );
        $messages[] = $message_item;
    }

    $stmt->close();

    // Retornar as mensagens em formato JSON
    echo json_encode($messages);
} else {
    echo "Parâmetros inválidos na URL.";
}
?>
