<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Seus estilos CSS aqui -->
</head>
<body>
    <div class="user-list">
        <?php
            include('Config.php');
            session_start();
            $userId = $_SESSION['ID'];

            $sql = "SELECT DISTINCT CASE WHEN remetente_id = $userId THEN destinatario_id WHEN destinatario_id = $userId THEN remetente_id END AS user_id FROM messages WHERE remetente_id = $userId OR destinatario_id = $userId";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $user_id = $row['user_id'];
                    $query_user = "SELECT * FROM usuarios WHERE ID = $user_id";
                    $result_user = $conn->query($query_user);

                    if ($result_user->num_rows > 0) {
                        $user_row = $result_user->fetch_assoc();
                        echo '<div class="user-message" data-user-id="'.$user_row['ID'].'">'.$user_row['nome'].'</div>';
                    }
                }
            } else {
                echo "Nenhum usuário encontrado.";
            }
            $conn->close();
        ?>
    </div>

    <div class="chat-container">
        <div id="chat">
            <!-- Aqui serão exibidas as mensagens do chat -->
        </div>

        <div class="input-container">
            <input type="text" id="message" placeholder="Digite sua mensagem...">
            <div id="send">Enviar</div>
        </div>
    </div>

    <script>
         $(document).ready(function(){
            function loadMessages(userId = null) {
                var url = 'get.php';
                if (userId !== null) {
                    url += '?ID=' + userId;
                }
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(response) {
                        $('#chat').html(response);
                    }
                });
            }

            loadMessages(); // Carregar mensagens de todos os usuários ao iniciar a página

            $('.user-message').click(function() {
                var userId = $(this).data('userId');
                loadMessages(userId);
            });

            $('#send').click(function(){    
                var message = $("#message").val();
                $.post("post.php", {text: message}, function(){
                    var userId = $('.user-message.active').data('userId');
                    loadMessages(userId);
                });
                $("#message").val("");
            });
        });
    </script>
</body>
</html>
