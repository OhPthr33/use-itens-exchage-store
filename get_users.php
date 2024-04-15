<?php
include('config.php');

// Certifique-se de que $userIdFromURL está definido
$userIdFromURL = isset($_GET['ID']) ? $_GET['ID'] : null;

// Consulta para obter usuários que enviaram mensagens
$sql = "SELECT DISTINCT u.ID, u.nome FROM usuarios u 
        INNER JOIN messages m ON u.ID = m.remetente_id OR u.ID = m.destinatario_id
        WHERE m.destinatario_id = '$userIdFromURL' OR m.remetente_id = '$userIdFromURL'";
$result = $conn->query($sql);

$users = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($users);
?>
