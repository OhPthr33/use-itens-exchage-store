<?php
session_start();
include('Config.php');

// Processar a atualização do perfil se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['newName'];
    $newEmail = $_POST['newEmail'];

    // Atualize os detalhes do perfil no banco de dados
    $email = $_SESSION['email'];
    $query = "UPDATE usuarios SET nome = '$newName', email = '$newEmail' WHERE email = '$email'";
    
    if ($conn->query($query) === TRUE) {
        echo "Perfil atualizado com sucesso";
    } else {
        echo "Erro ao atualizar o perfil: " . $conn->error;
    }

    // Redirecione o usuário para a página de perfil após a atualização
    header("Location: Perfil.php");
    exit;
}

// Obtenha os detalhes do perfil do usuário
$email = $_SESSION['email'];
$query = "SELECT * FROM usuarios WHERE email = '$email'";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['nome'];
    $email = $row['email'];
}

// Feche a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style8.css">
<body>
    <h1>Editar Perfil</h1>
    <form method="post" action="ProfileEdit.php">
        Nome:
        <input type="email" name="newName" value="<?php echo $name; ?>" required><br><br>
        
        E-mail:
        <input type="email" name="newEmail" value="<?php echo $email; ?>"><br><br>
        
        <input class="submit" type="submit" value="Atualizar Perfil">
    </form>
</body>
</html>

