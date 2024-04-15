<?php
include('config.php');
// Verifica se o ID do usuário já está na sessão
if (isset($_SESSION['user_id'])) {
    // Se sim, recupera o ID do usuário
    $user_id = $_SESSION['user_id'];
} else {
    // Se não, gera um novo ID para o usuário (pode ser um número aleatório, por exemplo)
    $user_id = mt_rand(1000, 9999);

    // Armazena o novo ID na sessão
    $_SESSION['user_id'] = $user_id;
}

// Agora $user_id contém o ID do usuário, que pode ser usado no restante do seu código
?>
