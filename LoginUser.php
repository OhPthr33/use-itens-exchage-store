<?php
session_start();
include ('Config.php');

// Verifica se o formulário foi submetido
    $email = $_POST["email"];
    $senha = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verifica se existe um usuário com o e-mail fornecido
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verifica se a senha fornecida corresponde à senha no banco de dados usando MD5
        if (md5($senha) === $row['senha']) {
            // Inicia a sessão e define os detalhes do usuário
            $_SESSION['email'] = $row['email'];
            $_SESSION['ID'] = $row['ID'];
            $_SESSION['user_type'] = $row['user_type'];
            $_SESSION['nome'] = $row['nome'];
            $_SESSION['user_id'] = $row['ID'];

            // Verifica se o usuário é um administrador e redireciona para a página apropriada
            if ($row['user_type'] == 1) {
                echo "<script>alert('Logado como administrador!'); window.location.href = 'admin.php';</script>";
            } else {
                echo "<script>alert('Logado com sucesso!'); window.location.href = 'Indice_Atual.php';</script>";
            }
        } else {
            // Senha inválida
            echo "<script>alert('Dados inválidos!'); window.location.href = 'PageLog.php';</script>";
        }
    } else {
        // Usuário não encontrado
        echo "<script>alert('Dados inválidos!'); window.location.href = 'PageLog.php';</script>";
    }
    
    // Fechando a conexão com o banco de dados
    mysqli_close($conn);
    ?>