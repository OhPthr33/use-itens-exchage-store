<?php 
session_start();
// Verifica se o usuário está logado e se o tipo de usuário está definido
if(isset($_SESSION['username']) && isset($_SESSION['user_type'])) {
    // Exibe o perfil do usuário
    echo "Bem-vindo, " . $_SESSION['username'] . "!";

    // Se o usuário não for um convidado, exibe o link para enviar mensagem
    if ($_SESSION['user_type'] != 'guest') {
        echo '<a href="../enviar_mensagem.php?user_id=123">Enviar mensagem</a>';
    }

    // Exibe o link para ver perfis de outros usuários
    echo '<a href="../ver_perfil.php?user_id=456">Ver perfil</a>';

    // Se o usuário for um administrador, exibe o link de administração
    if ($_SESSION['user_type'] == 'admin') {
        echo '<a href="../administracao.php">Administração</a>';
    }
} else {
    // Se o usuário não estiver logado, exibe o link de login
    echo '<a href="../login.php">Login</a>';
}
?>