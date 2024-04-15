<?php
include ('Config.php');
session_start();
// Suponha que o ID do usuário cujo perfil está sendo visualizado é passado como parâmetro na URL, por exemplo, profile.php?user_id=123
$userId = $_GET['user_id']; // Obtenha o ID do usuário da URL

// Use o ID do usuário para obter as informações do perfil desse usuário no banco de dados
// Faça uma consulta ao banco de dados para obter os detalhes do usuário com o ID especificado

// Exiba as informações do perfil do usuário
echo "Perfil do Usuário de ID $userId:";
// Aqui você exibe as informações do usuário, como nome, foto de perfil, biografia, etc.
?>