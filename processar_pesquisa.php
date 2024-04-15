<?php
include ('Config.php');

// Verifique se a solicitação é do tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verifique se o termo de pesquisa está definido
    if (isset($_POST['searchTerm'])) {
        
        // Limpe e valide o termo de pesquisa
        $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);
        $escapedSearchTerm = "%{$searchTerm}%";

        // Consulta para pesquisar no banco de dados com base no termo de pesquisa
        $stmt = $conn->prepare("SELECT * FROM items WHERE titulo LIKE ?");
        $stmt->bind_param("s", $escapedSearchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        // Retorna os resultados da pesquisa como JSON, por exemplo
        if ($result->num_rows > 0) {
            $rows = array();
            while ($row = $result->fetch_assoc()) { 
                $rows[] = $row;
            }
            echo json_encode($rows);
        } else {
            echo json_encode(array("message" => "Nenhum resultado encontrado"));
        }

        // Feche a declaração preparada
        $stmt->close();
        
    } else {
        echo "O termo de pesquisa não foi definido corretamente.";
    }

    // Feche a conexão com o banco de dados
    $conn->close();
}
?>
<script>

success: function(response) {
    // Manipule a resposta da pesquisa aqui e exiba os resultados na página
    var resultadosDiv = document.getElementById('resultados');

    if (response !== "Nenhum resultado encontrado") {
        // Converta a string JSON em um objeto JavaScript
        var resultados = JSON.parse(response);

        // Limpe o conteúdo atual da div de resultados
        resultadosDiv.innerHTML = "";

        // Itere sobre os resultados e adicione-os à div
        resultados.forEach(function(item) {
            // Crie elementos HTML para exibir informações do item (ajuste conforme necessário)
            var resultadoItem = document.createElement('div');
            resultadoItem.innerHTML = '<strong>' + item.titulo + '</strong>: ' + item.descricao;

            // Adicione o elemento à div de resultados
            resultadosDiv.appendChild(resultadoItem);
        });
    } else {
        // Se nenhum resultado for encontrado, exiba uma mensagem na div de resultados
        resultadosDiv.innerHTML = "Nenhum resultado encontrado";
    }
}

</script>
