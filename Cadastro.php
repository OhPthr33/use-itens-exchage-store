<?php
// Inclui o arquivo de configuração que contém as configurações do banco de dados
include("config.php");
// Inicia a sessão PHP
session_start();

// Obtém os valores do formulário de cadastro
$nome = $_POST["nome"];
$email = $_POST["e-mail"];
$telefone = $_POST["telefone"]; // Novo campo de telefone
$password = $_POST["password"];
$confirmaSenha = $_POST["confirmaSenha"];

// Prepara uma consulta para verificar se o usuário já existe no banco de dados
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o usuário já existe
if ($result->num_rows > 0) {
    // Se o usuário já existir, exibe uma mensagem e redireciona de volta para a página de cadastro
    echo "<script>alert('Usuário já existente!'); window.location.href = 'PageCad.php';</script>";
    exit();
} else {
    // Se o usuário não existir, verifica se as senhas coincidem
    if ($password !== $confirmaSenha) {
        // Se as senhas não coincidirem, exibe uma mensagem e redireciona de volta para a página de cadastro
        echo "<script>alert('As senhas não coincidem. Tente novamente.'); window.location.href = 'PageCad.php';</script>";
        exit();
    } else {
        // Se as senhas coincidirem, cria uma senha hash usando md5
        $hashed_password = md5($password);
        // Define o tipo de usuário como "user"
        $user_type = "user";

        // Insere o novo usuário no banco de dados
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, telefone, senha, user_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $email, $telefone, $hashed_password, $user_type);
        $stmt->execute();

        // Verifica se o cadastro foi bem-sucedido e exibe uma mensagem de sucesso, caso contrário, exibe uma mensagem de erro
        if ($stmt) {
            echo "<script>alert('Cadastrado com sucesso!'); window.location.href = 'PageLog.php';</script>";
            exit();
        } else {
            echo "<script>alert('Erro ao cadastrar usuário!'); window.location.href = 'PageCad.php';</script>";
            exit();
        }
    }
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
