<?php
include('config.php');
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['user_id'])) {
    $id_usuario_logado = $_SESSION['user_id'];

    
// Verifica se as variáveis $_GET estão definidas
if (isset($_GET['item_desejado_id'], $_GET['id_usuario'], $_GET['user_id'], $_GET['tipo_troca'])) {

    // Atribui valores das variáveis $_GET a variáveis locais
    $id_desejado = $_GET['item_desejado_id'];
    $id_usuario = $_GET['id_usuario'];
    $user_id = $_GET['user_id'];
    $tipo_trad = $_GET['tipo_troca'];

    // Consulta para obter os produtos adicionados pelo usuário logado
    $sql = "SELECT * FROM items WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);

    // Verifica se a preparação da consulta foi bem-sucedida
    if ($stmt) {
        $stmt->bind_param("i", $id_usuario_logado);
        $stmt->execute();

        // Obtém o resultado da consulta
        $result = $stmt->get_result();

        echo '<h1>Produtos Disponiveis para troca</h1>';
        
        echo '<div class="produto-container">';

        // Verifica se a consulta retornou algum resultado
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id_prod = $row['id'];
                
                // Exibe os detalhes do produto, incluindo imagem, título e descrição
                echo '<link rel="stylesheet" type="text/css" href="./css/Style1.css" media="screen">';
                echo '<div class="produto">';
                echo "<a href='Indice_Atual.php?id_prod=$id_prod'>";

                // Verifica se a chave "imagem" está definida no array $row
                if (isset($row['imagem'])) {
                    echo '<img src="' . $row['imagem'] . '" alt="Produto">';
                } else {
                    echo '<img src="caminho_para_imagem_padrao.jpg" alt="Imagem Padrão">';
                }

                echo '<h2>' . $row['titulo'] . '</h2>';
                echo '<p>' . $row['descricao'] . '</p>';
                // Adicione aqui mais informações ou ações que deseja exibir
                echo '</div>';
            } if ($id_prod > 0){
                  // Insere o ID na tabela trades
            $sql_insert = "INSERT INTO trades (item_oferecido_id, item_desejado_id, proponente_id, aceitante_id, tipo_trad) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);

            if ($stmt_insert) {
                $stmt_insert->bind_param("iiiis", $id_prod, $id_desejado, $user_id, $id_usuario, $tipo_trad);
                $stmt_insert->execute();
            }else {
                echo 'Erro ao inserir na tabela trades.';
            }
            echo '</div>';
        } else {
            echo "Nenhum produto adicionado pelo usuário logado.";
        }

        // Fecha a declaração preparada
        $stmt->close();
    } else {
        echo 'Nenhum produto disponível para troca.';
    }
}
    else{
        echo 'erro na consulta.';
    }
} else {
    echo 'Usuário não está logado.';
}
}

// Fecha a conexão com o banco de dados
$conn->close();
?>


