<?php
include ('config.php');

$id = null; // Define $id como nulo por padrão

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Consulta para obter o ID com base no email
    $sql = "SELECT ID FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Verifique se alguma linha foi retornada antes de acessar os resultados
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id = $row['ID'];
        } else {
            echo"Erro" . header('location: PageCad.php');
        }

        // Feche a conexão com o banco de dados
        $stmt->close();
    } else {
        // Caso a preparação da consulta falhe
        echo "Erro na consulta SQL: " . $conn->error;
    }
} else {
    // Lida com o caso em que 'email' não está definido na sessão
}

?>