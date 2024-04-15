<!DOCTYPE html>
<html>
<head>
    <title>Perfil de Usuário</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/style7.css" media="screen">
</head>
<body>
    <div class="img">
        <a href="Indice_Atual.php">
            <img src="./images/logo-slogan4-1.png" alt="Logo" style="height: 70px; position: relative; left: 5px; background-color: rgb(126, 212, 126); border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) ;top:4px">
        </a>
    </div>
    <div class="container">
    <?php
session_start();
include ('Config.php');

$nome = '';
$email = '';

if (isset($_GET['ID'])) {
    $id = $_GET['ID'];

    // Consulta para obter os detalhes do usuário com base no ID
    $sql = "SELECT * FROM usuarios WHERE ID = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nome = $row['nome'];
            $email = $row['email'];
            $tele = $row['Telefone'];
        } else {
            header('Location: PageLog.php');
            exit();
        }

        $stmt->close();
    } else {
        echo "Erro na preparação da consulta: " . $conn->error;
    }
}
?>

    <h1>Perfil de <?php echo $nome; ?></h1>
    <p><strong>Nome:</strong> <?php echo htmlspecialchars($nome); ?></p>
    <p><strong>E-mail:</strong> <?php echo htmlspecialchars($email); ?></p>
    <p><strong>Contacto:</strong> <?php echo htmlspecialchars($tele); ?></p>


    <?php
    
    if (isset($_SESSION['ID']) == isset($_GET['ID']) || $row['user_type']==1){
        $id_logado = $_SESSION['ID'];
        $id_na_url = $_GET['ID'];

        if ($id_na_url == $id_logado){
        echo '<div class="column">';
        echo '<a href="ProfileEdit.php?ID='. $id_na_url . '">Editar Perfil</a>';
        echo '<a href="Logout.php">Logout</a>';
        echo '<a href="Adc_Item.php">Adicionar Produtos</a>';
        echo '<a href="Solicitacoes.php">Solicitações</a>';
        echo '<a href="Solicita_aceite.php">Solicitações aceites</a>';
        echo "<a href='delete_account.php?recipient_id=" . $id . "' onclick='confirmarExclusao(); return false;'>Excluir conta</a>";
        }
    }

    ?>
        <a href="messages.php?ID=<?php echo $id; ?>">Enviar mensagem</a>
        <?php
        if ($row['user_type'] == 1){
        echo "<a href='admin.php'>administração</a>";
        echo '</div>';
}else{
}
?>
        <script>
        function confirmarExclusao() {
            if (confirm("Tem certeza de que deseja excluir sua conta?")) {
                window.location.href = "delete_account.php?id=<?php echo $id; ?>";
            } else {
            }
        }
        </script>
    </div>

    <h2>Produtos Adicionados</h2>

    <?php
    include('User_id.php');
    $products_sql = "SELECT * FROM items WHERE usuario_id = ?";
    $products_stmt = $conn->prepare($products_sql);

    if ($products_stmt) {
        $products_stmt->bind_param("i", $id);
        $products_stmt->execute();
        $products_result = $products_stmt->get_result();

        if ($products_result->num_rows > 0) {
            while ($product_row = $products_result->fetch_assoc()) {
                $product_id = $product_row['id'];
                $usuario_id = $product_row['usuario_id'];

                if ($product_row["Estado"] == 'D') {
        
                echo "<div class='p_container'>";
                echo "<div class='produto'>";
                echo "<a href='Itens.php?id=$product_id&id_usuario=$usuario_id&user_id=$user_id'>";
                echo "<p>Nome do Produto: " . htmlspecialchars($product_row['titulo']) . "</p>";
                echo "<p>Descrição do Produto: " . htmlspecialchars($product_row['descricao']) . "</p>";
                echo "<img src='" . htmlspecialchars($product_row['imagem']) . "' alt='Imagem do Produto' style='max-width: 200px; max-height: 200px;' />";
                echo '</a>';
                echo '</form>';
                echo "</div>";
                echo "</div>";
                }
            }
        } else {
            echo "<p>Nenhum produto adicionado ainda.</p>";
        }

        $products_stmt->close();
    } else {
        echo "Erro na preparação da consulta: " . $conn->error;
    }

    // Verificar se o botão de exclusão de produto foi clicado
    if (isset($_POST['excluir_produto'])) {
        $delete_product_id = $_GET['delete_id'];
        $delete_product_sql = "DELETE FROM items WHERE id = ?";
        $delete_product_stmt = $conn->prepare($delete_product_sql);
        
        if ($delete_product_stmt) {
            $delete_product_stmt->bind_param("i", $delete_product_id);
            if ($delete_product_stmt->execute()) {
                echo "Produto excluído com sucesso.";
            } else {
                echo "Erro ao excluir o produto: " . $delete_product_stmt->error;
            }
            $delete_product_stmt->close();
        } else {
            echo "Erro na preparação da consulta: " . $conn->error;
        }
    }
    ?>
    </body>
</html>
