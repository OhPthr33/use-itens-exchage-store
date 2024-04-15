<?php
    include ('Config.php');

    if(isset($_GET['ID'])) {
        $userId = $_GET['ID'];
        
        // Consulta para recuperar as mensagens do usuário com o ID específico
        $sql = "SELECT * FROM messages WHERE remetente_id = '$userId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $remetente_id = $row["remetente_id"];
                $sql_user = "SELECT nome FROM usuarios WHERE ID = '$remetente_id'";
                $result_user = $conn->query($sql_user);
                if ($result_user->num_rows > 0) {
                    $row_user = $result_user->fetch_assoc();
                    $nome_remetente = $row_user["nome"];
                    echo "<p><strong>Remetente:</strong> " . $nome_remetente . " - <strong>Data e Hora:</strong> " . $row["data_hora_envio"] . " - <strong>Mensagem:</strong> " . $row["conteudo"] . "</p>";
                } else {
                    echo "<br>Nome do remetente não encontrado.</br>";
                }
            }
        } else {
            echo "0 results";
        }

        $conn->close();
    }
?>
