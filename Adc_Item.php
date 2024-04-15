<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style6.css">
<?php
include('Config.php');

$id = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $stmt = $conn->prepare("SELECT ID FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $id = $row['ID'];
    }

    $nome = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $regiao = isset($_POST['regiao']) ? $_POST['regiao'] : null;
    $condicao = $_POST['condicao'];
    $tipo = $_POST['tipo'];
    $estado = "Pendente";

 // Obter o ID da Região
$stmt_regiao = $conn->prepare("SELECT id_região FROM região WHERE nome_reg = ?");
if (!$stmt_regiao) {
    die("Erro na preparação da consulta da região: " . $conn->error);
}

$stmt_regiao->bind_param("s", $regiao);
$stmt_regiao->execute();
$result_regiao = $stmt_regiao->get_result();

if ($result_regiao === false) {
    die("Erro ao executar a consulta da região: " . $stmt_regiao->error);
}

$row_regiao = $result_regiao->fetch_assoc();

// Adicione mensagens de depuração
echo "Região buscada: " . $regiao . "<br>";
echo "Número de linhas retornadas: " . $result_regiao->num_rows . "<br>";

// Verifica se há resultados antes de acessar o índice
if ($row_regiao !== null) {
    $id_regiao = $row_regiao['id_região'];
    echo "ID da Região encontrado: " . $id_regiao . "<br>";
} else {
    die("Nenhum resultado encontrado para a região com nome: " . $regiao);
}

$stmt_regiao->close(); // Feche a consulta após o uso

// Obter o ID da condição
$stmt_condicao = $conn->prepare("SELECT id_cond FROM condição WHERE descrição = ?");
if ($stmt_condicao === false) {
    die("Erro na preparação da consulta da condição: " . $conn->error);
}

$stmt_condicao->bind_param("s", $condicao);
$stmt_condicao->execute();
$result_condicao = $stmt_condicao->get_result();

// Verifica se a execução foi bem-sucedida
if ($result_condicao === false) {
    die("Erro ao executar a consulta da condição: " . $stmt_condicao->error);
}

$row_condicao = $result_condicao->fetch_assoc();

// Verifica se há resultados antes de acessar o índice
if ($row_condicao !== null) {
    $id_cond = $row_condicao['id_cond'];
} else {
    die("Nenhuma correspondência encontrada para a condição: " . $condicao);
}

$stmt_condicao->close(); // Feche a consulta após o uso

// Obter o ID da categoria
$categoria = isset($_POST['categoria']) ? $_POST['categoria'] : null;

// Verificar se a categoria foi selecionada
if ($categoria === null) {
    die("Por favor, selecione uma categoria.");
}

$stmt_categoria = $conn->prepare("SELECT id_categoria FROM categoria WHERE nome = ?");

// Verifica se a preparação da consulta da categoria foi bem-sucedida
if ($stmt_categoria === false) {
    die("Erro na preparação da consulta da categoria: " . $conn->error);
}

$stmt_categoria->bind_param("s", $categoria);
$stmt_categoria->execute();
$result_categorias = $stmt_categoria->get_result();

// Verifica se a execução foi bem-sucedida
if ($result_categorias === false) {
    die("Erro ao executar a consulta da categoria: " . $stmt_categoria->error);
}

$row_categoria = $result_categorias->fetch_assoc();

// Verifica se há resultados antes de acessar o índice
if ($row_categoria !== null) {
    $id_categ = $row_categoria['id_categoria'];
} else {
    die("Nenhum resultado encontrado para a categoria.");
}

$stmt_categoria->close(); 

    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); // Crie o diretório se ele não existir
        }
        $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
            echo "O arquivo " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " foi carregado com sucesso.";

            // Insira o item
            $stmt = $conn->prepare("INSERT INTO items (usuario_id, titulo, descricao, imagem, id_regiao, Estado, id_categ, id_cond, Tipo_trad) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

            // Verifica se a preparação da consulta foi bem-sucedida
            if ($stmt === false) {
                die("Erro na preparação da consulta: " . $conn->error);
            }

            // Tenta vincular os parâmetros
            $bindResult = $stmt->bind_param("isssisiis", $id, $nome, $descricao, $targetFile, $id_regiao, $estado, $id_categ, $id_cond, $tipo);

            // Verifica se a vinculação foi bem-sucedida
            if ($bindResult === false) {
                die("Erro ao vincular parâmetros: " . $stmt->error);
            }

            // Executa a consulta
            $executeResult = $stmt->execute();

            // Verifica se a execução foi bem-sucedida
            if ($executeResult === false) {
                die("Erro ao executar a consulta: " . $stmt->error);
            } else {
                echo "Consulta executada com sucesso!";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Desculpe, houve um erro ao carregar seu arquivo.');</script>";
        }
    } else {
        echo "<script>alert('Nenhum arquivo foi carregado.');</script>";
    }
    // Adicione o redirecionamento aqui
    header("Location: Indice_Atual.php");
    exit(); 

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Produto</title>
    <style>
        /* Adicione estilos CSS para melhorar a aparência do formulário, se desejar */
    </style>
</head>
<body>
    <h2>Adicionar Produto</h2>
    <?php
    // Adicione mensagens de feedback aqui, se necessário
    ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        Título: <input class="titulo" type="text" name="titulo" required>
        Região:
<select class="regiao" name="regiao" id="regiao">
    <?php
    $stmt_regiao = $conn->prepare("SELECT * FROM região");
    $stmt_regiao->execute();
    $result_regiao = $stmt_regiao->get_result();

    while ($row_regiao = $result_regiao->fetch_assoc()) {
        echo "<option value='" . $row_regiao['nome_reg'] . "'>" . $row_regiao['nome_reg'] . "</option>";
    }

    $stmt_regiao->close();
    ?>
</select>
        Descrição: <input class="descricao" type="text" name="descricao" required>
        Condição:
        <select class="condicao" name="condicao" required>
            <option value="mal">mal</option>
            <option value="suficiente" selected>suficiente</option>
            <option value="bom">bom</option>
        </select>
        Tipo:
        <select class="tipo" name="tipo" required>
            <option value="doacao" selected>Doação</option>
            <option value="troca">Troca</option>
        </select>
        Categoria:
        <select class="categoria" name="categoria" id="categoria" required>
            <?php
            $stmt_categorias = $conn->prepare("SELECT * FROM categoria");
            $stmt_categorias->execute();
            $result_categorias = $stmt_categorias->get_result();

            while ($row_categoria = $result_categorias->fetch_assoc()) {
                echo "<option value='" . $row_categoria['nome'] . "'>" . $row_categoria['nome'] . "</option>";
            }

            $stmt_categorias->close();
            ?>
        </select>

        Imagem: <input class="file" type="file" name="fileToUpload" id="fileToUpload" required>

        <input class="submit" type="submit" value="Adicionar Produto">
    </form>
</body>
</html>
