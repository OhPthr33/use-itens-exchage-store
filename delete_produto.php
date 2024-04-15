<?php
session_start();
include('Config.php');

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    if (isset($_POST['confirm_delete'])) {
        // Confirmação recebida, execute a exclusão do produto
        $sql = "DELETE FROM items WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            if ($stmt->execute()) {
                echo "<script>alert('Produto excluído!'); window.location.href = '../indice_atual.php';</script>";
            } else {
                echo "<script>alert('Erro ao excluir o produto: '" . $stmt->error;
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
            <link rel="stylesheet" href="./css/style6.css">
            <title>Confirmação de Exclusão</title>
        </head>
        <body>

            <!-- Formulário de confirmação -->
            <form method="post">
                <p>Tem certeza de que deseja excluir este produto?</p>
                <input class="submit" type="submit" name="confirm_delete" value="Confirmar">
                <a href="../indice_atual.php">Cancelar</a>
            </form>

        </body>
        </html>

        <?php
    }
} else {
    echo "ID do produto não está definido na URL.";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
