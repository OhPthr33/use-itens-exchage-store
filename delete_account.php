<?php
session_start();
include('Config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if (isset($_POST['confirm_logout'])) {
        // Confirmação recebida, execute a exclusão da conta
        $sql = "DELETE FROM usuarios WHERE ID = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                session_destroy();
                header('Location: ../PageLog.php');
                exit();
            } else {
                echo "Erro ao excluir a conta: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Erro na preparação da consulta: " . $conn->error;
        }
    } else {
        // Página de confirmação, exibir o formulário de confirmação
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirmação de Exclusão</title>
        </head>
        <body>

            <!-- Formulário de confirmação -->
            <form method="post">
                <p>Tem certeza de que deseja excluir sua conta?</p>
                <input type="submit" name="confirm_logout" value="Confirmar">
                <a href="../HomePage.php">Cancelar</a>
            </form>

        </body>
        </html>

        <?php
    }
} else {
    echo "ID do usuário não está definido na URL.";
}
?>
