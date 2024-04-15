<?php
include ('Config.php');
include ('User_id.php');
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/Style5.css">
</head>

    <body>
    <div class="Faixa">
        <div class="img">
            <a href="Indice_Atual.php">
                <img src="./images/logo-slogan4-1.png" alt="Logo" style="height: 70px; position: relative; left: 5px; background-color: rgb(126, 212, 126); border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) ;top:4px">
            </a>
        </div>
   
        <div class="button1" type="button" id="button1">
            <svg class="svgIcon" viewBox="0 0 512 512" height="1em" xmlns="http://www.w3.org/2000/svg">
                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm50.7-186.9L162.4 380.6c-19.4 7.5-38.5-11.6-31-31l55.5-144.3c3.3-8.5 9.9-15.1 18.4-18.4l144.3-55.5c19.4-7.5 38.5 11.6 31 31L325.1 306.7c-3.2 8.5-9.9 15.1-18.4 18.4zM288 256a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z"></path>
            </svg>
            Explore
        </div>

        <?php
// Consulta para obter todas as categorias
$sqlCategorias = "SELECT * FROM categoria";
$resultCategorias = $conn->query($sqlCategorias);

// Verifica se a consulta foi bem-sucedida
if (!$resultCategorias) {
    die("Erro na consulta: " . $conn->error);
}

// Exibe as categorias no menu suspenso
if ($resultCategorias->num_rows > 0) {
    echo '<div class="categorias-dropdown" id="categoriasDropdown">';
    while ($rowCategoria = $resultCategorias->fetch_assoc()) {
        echo '<a href="#">' . $rowCategoria['nome'] . '</a>';
    }
    echo '</div>';
} else {
    echo 'Nenhuma categoria encontrada.';
}
?>
        <script>
            // Define variáveis JavaScript com base em valores PHP
            var isSessionStarted = '<?php echo isset($_SESSION['email']) ? 'true' : 'false'; ?>';
            var id = '<?php echo $id; ?>';

            // Obtém o elemento de link de perfil
            var profileLink = document.getElementById('profileLink');

            // Define a função para definir o link de perfil com base no estado da sessão
            function setProfileLink() {
                if (isSessionStarted == 'true' && id !== '') {
                    // Define o link do perfil com base no ID do usuário
                    profileLink.href = 'Perfil.php?ID=' + id;
                } else {
                    // Se a sessão não estiver iniciada, redireciona para a página de login
                    profileLink.href = 'PageLog.php';
                }
                // Exibe o link do perfil no console para depuração
                console.log("profileLink.href:", profileLink.href);
            }
            // Chama a função para definir o link do perfil ao carregar a página
            setProfileLink();
        </script>

        <div class="search">
            <div class="search-box">
                <div class="search-field">
                    <input id="search-input" placeholder="Pesquisar..." class="input" type="text">
                    <div class="search-box-icon">
                        <button class="btn-icon-content" onclick="pesquisar()">
                            <i class="search-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 512 512">
                                    <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" fill="#fff"></path>
                                </svg>
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Adicione a div de resultados abaixo do campo de pesquisa -->
            <div id="resultados" class="resultados"></div>
        </div>

        <script>
            function pesquisar() {
                var searchTerm = document.getElementById('search-input').value;
                window.location.href = 'resultados_pesquisa.php?searchTerm=' + encodeURIComponent(searchTerm);
            }
        </script>

        <script>
            // Adiciona um listener de evento para o botão e lida com o clique do botão
            document.addEventListener('DOMContentLoaded', function () {
                const button1 = document.getElementById('button1');
                const categoriasDropdown = document.getElementById('categoriasDropdown');

                // Adiciona um evento de clique ao botão1
                button1.addEventListener('click', (event) => {
                    event.preventDefault(); // Evita que a página seja atualizada
                    // Alterna a exibição do menu suspenso de categorias
                    if (categoriasDropdown.style.display === 'block') {
                        categoriasDropdown.style.display = 'none';
                    } else {
                        categoriasDropdown.style.display = 'block';
                    }
                });

                // Adiciona um evento de clique à janela para fechar o menu suspenso se o clique não for no botão1
                window.addEventListener('click', (event) => {
                    if (!event.target.matches('#button1')) {
                        categoriasDropdown.style.display = 'none';
                    }
                });
            });
        </script>

        <?php
include ('Config.php');

if(isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] == 1) {
        // Contar o número de usuários
        $sqlUserCount = "SELECT COUNT(*) as total_users FROM usuarios";
        $resultUserCount = $conn->query($sqlUserCount);
        $rowUserCount = $resultUserCount->fetch_assoc();
        $totalUsers = $rowUserCount['total_users'];

        // Contar o número de produtos
        $sqlProductCount = "SELECT COUNT(*) as total_products FROM items";
        $resultProductCount = $conn->query($sqlProductCount);
        $rowProductCount = $resultProductCount->fetch_assoc();
        $totalProducts = $rowProductCount['total_products'];

        // Exibir o número de usuários e produtos
        echo '<div class="home">';
        echo '<div style="padding: 10px; margin: 10px; margin-top:20px;">';
        echo "<style>Bem-vindo à página de administração!<br></style>";
        echo "Número de usuários: " . $totalUsers . "<br>";
        echo "Número de produtos adicionados: " . $totalProducts . "<br>";

    // consulta SQL para recuperar os produtos pendentes
    $sqlProdutosPendentes = "SELECT * FROM items WHERE Estado = 'P'";
    $resultProdutosPendentes = $conn->query($sqlProdutosPendentes);

    // Verifica se a consulta foi bem-sucedida
    if (!$resultProdutosPendentes) {
        die("Erro na consulta de produtos pendentes: " . $conn->error);
    }

    // Exibe os produtos pendentes, se houver algum
        // Consulta o banco de dados para obter os produtos pendentes
        $sql = "SELECT * FROM items";
        $result = $conn->query($sql);


        // Exibe produtos se houver algum encontrado, caso contrário, exibe uma mensagem de nenhum produto encontrado
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id_item = $row['id'];
                $id_usuario = $row['usuario_id'];
                if ($row['Estado']=='P'){
                // Exibe os detalhes do produto, incluindo imagem, título e descrição
                ?>
                    <div class="produto">
                        <a href='prod_process.php?id=<?php echo $id_item; ?>&id_usuario=<?php echo $id_usuario; ?>&user_id=<?php echo $user_id; ?>'>
                            <img src="<?php echo $row['imagem']; ?>" alt="Produto">
                            <h2><?php echo $row['titulo']; ?></h2>
                            <p><?php echo $row['descricao']; ?></p>
                            <a href="messages.php?destinatarioId=<?php echo $id_usuario; ?>" style="display: block; margin-top: 10px; background-color: #4CAF50; color: white; padding: 10px; text-align: center; text-decoration: none; border-radius: 5px;">Enviar mensagem</a>
                        </a>
                    </div>
                <?php
            }
        }
        } else {
            // Se nenhum produto for encontrado, exibe uma mensagem correspondente
            echo "Nenhum produto pendente encontrado";
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        } else {
        // Se o usuário não é um administrador, redirecione para uma página de acesso negado ou para a página de login
        header("Location: PageLog.php");
        exit();
    }
} else {
    // Se o tipo de usuário não estiver definido na sessão, redirecione para a página de login
    header("Location: PageLog.php");
    exit();
}
?>
</html>

<style>
.produto-container {
display: flex;
justify-content: space-around;
align-items: center;
flex-direction: row; /* Adicionada esta linha para alinhar na horizontal */
flex-wrap: wrap; /* Adicionada esta linha para quebrar a linha se não couberem todos */
}

.produto {
border-radius: 20px;
background-color: #fff;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
margin: 10px;
padding: 20px;
width: 250px;
height: 450px;
transition: transform 0.2s;
}

.produto a {
text-decoration: none;
color: #333;
}

.produto img {
max-width: 100%;
border-radius: 5px;
height: auto;
}

.produto:hover {
transform: scale(1.05);
}

.produto h2 {
font-size: 24px;
margin: 10px 0;
}

.produto p {
font-size: 16px;
margin: 10px 0;
}

.produto .price {
font-size: 18px;
font-weight: bold;
color: #333;
}

@media screen and (max-width: 768px) {
.produto {
 flex-basis: 45%;
}
}

@media screen and (max-width: 480px) {
.produto {
 flex-basis: 100%;
}
}


 .categorias-dropdown {
     max-height: 200px; 
     overflow-y: auto;
     z-index: 9999;
 }

 .categorias-dropdown a {
     display: block;
     padding: 10px;
     text-decoration: none;
     color: #333;
 }
</style>