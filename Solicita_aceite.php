<?php
include('Config.php');
session_start();

ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo 'Usuário não está logado.';
    exit;  // Encerra o script se o usuário não estiver logado
}

$id_usuario_logado = $_SESSION['user_id'];

$sql = "SELECT 
    u.nome AS nome_usuario,
    i.titulo AS titulo_item,
    i.imagem AS imagem_item,
    i.usuario_id AS usuario_id_item,
    t.id AS trade_id,
    t.item_oferecido_id,
    t.item_desejado_id,
    t.proponente_id,
    t.aceitante_id,
    t.Tipo_trad,
    t.estado  -- Adicionado o campo 'estado'
FROM 
    trades t
INNER JOIN 
    items i ON (
        (t.proponente_id = i.usuario_id OR t.aceitante_id = i.usuario_id)
        AND 
        (t.item_oferecido_id = i.id OR t.item_desejado_id = i.id)
    ) 
INNER JOIN 
    usuarios u ON i.usuario_id = u.ID 
    WHERE  
    (t.aceitante_id = '$id_usuario_logado' AND t.aceitante_id = u.ID)
    OR
   (t.proponente_id = " . $id_usuario_logado . " AND t.aceitante_id = u.ID)
   OR
   (t.aceitante_id = " . $id_usuario_logado . " AND t.Tipo_trad = 'doacao')
   AND t.estado = 'a'";
  // Adicionada a condição para 'estado'

  $result = mysqli_query($conn, $sql);

  if (!$result) {
      die('Erro na execução da consulta: ' . mysqli_error($conn));
  }
  
  ?>
  
  <!DOCTYPE html>
  <html lang="pt-BR">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" type="text/css" href="./css/style7.css" media="screen">
      <title>Solicitações e Doações</title>
      <style>
          /* Estilos CSS permanecem os mesmos */
      </style>
  </head>
  <body>
      <h1>Solicitações e Doações</h1>
  
      <?php
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            if ($row['estado']=='a'){
              echo '<div class="transacao">';
              echo '<p><strong>Nome do Usuário:</strong> ' . htmlspecialchars($row['nome_usuario']) . '</p>';
              echo '<p><strong>Título do Item:</strong> ' . htmlspecialchars($row['titulo_item']) . '</p>';
              
              // Imagem do item
  echo '<p><strong>Imagem do Item Desejado:</strong></p>';
  if ($row['imagem_item'] !== null) {
      echo '<img src="' . $row['imagem_item'] . '" alt="Item">';
  } else {
      echo '<p>Imagem não disponível</p>';
  }
  
  // Verifica se o item_desejado é nulo antes de buscar a imagem
  if ($row['item_desejado_id'] !== null) {
      // Consulta para obter a imagem do Item Desejado
      $sqlItemDesejado = "SELECT imagem FROM items WHERE id = " . $row['item_oferecido_id'];
      $resultItemDesejado = mysqli_query($conn, $sqlItemDesejado);
      $rowItemDesejado = mysqli_fetch_assoc($resultItemDesejado);
  
      // Imagem do Item Desejado
      echo '<p><strong>Item Oferecido:</strong></p>';
      if ($rowItemDesejado['imagem'] !== null) {
          echo '<img src="' . $rowItemDesejado['imagem'] . '" alt="Item Desejado">';
      } else {
          echo '<p>Imagem não disponível</p>';
      }
  }
              // Verifica se a chave "estado" está definida e se a solicitação foi aceita
              if (isset($row['estado']) && $row['estado'] === 'a') {
                  echo '<p>Solicitação Aceita</p>';
              } else {
                  // Exibe os botões de aceitar ou recusar
                  echo '<form method="post" action="processar_solicitacao.php">';
                  echo '<input type="hidden" name="trade_id" value="' . $row['trade_id'] . '">';
                  echo '<button type="submit" name="aceitar" value="1" class="aceitar-botao">Aceitar</button>';
                  echo '<button type="submit" name="recusar" value="1" class="recusar-botao">Recusar</button>';
                  echo '</form>';
              }
  
              // Adiciona outros campos conforme necessário
              echo '<hr>';
              echo '</div>';
          }
        }
      } else {
          echo '<p>Nenhuma solicitação ou doação encontrada.</p>';
      }
  
  
      // Fecha a conexão com o banco de dados
      mysqli_close($conn);
      ?>
  </body>
  </html>