<?php
// Inicie a sessão
session_start();

// Destrua a sessão
session_destroy();

// Redirecione para a página de login ou outra página desejada após o logout
header("Location: ../PageLog.php");
exit; // Certifique-se de sair após redirecionar
?>