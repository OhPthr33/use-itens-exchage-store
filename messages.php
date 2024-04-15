<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
         body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #fafafa;
            font-family: Arial, sans-serif;
        }

        .chat-container {
            position: relative;
            width: 1000px;
            height: 500px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            background-color: #ffffff;
            border: 1px solid #dbdbdb;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        #chat {
            position: relative;
            left: 289px;
            width: 65.5%;
            height: 90.5%;
            border: 1px solid #dbdbdb;
            overflow: auto;
            padding: 10px;
            background-color: #ffffff;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        /* Adicione este trecho ao seu CSS existente */
.user-messages {
    position: absolute;
    width: 24%;
    height: 77%;
    border: 1px solid #dbdbdb;
    overflow-y: auto;
    padding: 10px;
    background-color: #ffffff;
    border-radius: 5px;
    margin-bottom: 20px;
}

        .input-container {
            display: flex;
            width: 100%;
        }

        #message {
            flex: 1;
            padding: 10px;
            border: 1px solid #dbdbdb;
            border-radius: 5px;
            margin-right: 10px;
        }

        #send {
            padding: 10px 20px;
    border: 1px solid #dbdbdb;
    background-color: lightgreen;
    transition: background-color 0.3s ease;
    border-radius: 5px;
    cursor: pointer;
        }

        #send:hover {
            background-color: #f2f2f2;
        }

        .barra {
            padding: 10px;
            width: 100px;
        }

        .user {
            position: relative;
    top: 29px;
    right: -6.6%;
    width: 17%;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    z-index: 1;
        }
        /* Adiciona um estilo básico para os itens da barra lateral */
        .user-list-item {
            padding: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 110px;
    border-bottom-color: black;
    margin-bottom: 10px;
    background-color: lightgreen;
    border-radius: 8px;
}

.user-list-item:hover {
    background-color: #f2f2f2;
}

        .user-list-container {
            overflow-y: auto; /* Torna a barra lateral rolável */
            max-height: 77%; /* Altura máxima da barra lateral */
        }
    </style>
    </head>
<body>
    <img src="../images/Clover.jpg" style="width: 350px;
    z-index: auto;
    position: absolute;
    left: -15px;
    top: -15px;">
    <div class="chat-container">
    <script>
$(document).ready(function () {
    var userIdFromURL = <?php echo isset($_GET['ID']) ? (int)$_GET['ID'] : 'null'; ?>;
    var recipientIdFromURL = <?php echo isset($_GET['recipient_id']) ? (int)$_GET['recipient_id'] : 'null'; ?>;
    var socket = new WebSocket("ws://127.0.0.1:8080/"); // Use a porta correta, no exemplo é 8080

    $(document).on('click', '.user-list-item', function () {
        var clickedUserId = $(this).data('user-id');
        loadMessages(userIdFromURL, clickedUserId);
    });

    function loadMessages(senderId, recipientId) {
        $.ajax({
            type: 'GET',
            url: 'get.php',
            data: {
                'ID': senderId,
                'recipient_id': recipientId
            },
            dataType: 'json',
            success: function (messages) {
                // Limpa o conteúdo atual do chat
                $('#chat').empty();

                // Adiciona as mensagens ao chat
                for (var i = 0; i < messages.length; i++) {
                    var message = messages[i];
                    var content = '[' + message.data_hora_envio + '] ' + message.remetente_id + ': ' + message.conteudo;
                    $('#chat').append('<div>' + content + '</div>');
                }
            },
            error: function (error) {
                console.error('Erro ao carregar mensagens: ' + error.responseText);
            }
        });
    }

    // Carrega as mensagens do usuário especificado na URL
    if (userIdFromURL && !isNaN(userIdFromURL)) {
        loadMessages(userIdFromURL, recipientIdFromURL);
    } else {
        console.error("ID de usuário inválido na URL.");
    }

    $('.user-message').click(function () {
        var userId = $(this).data('user-id');
        loadMessages(userId, recipientIdFromURL);
    });

    function sendMessage(userId, recipientId, message) {
        if (socket.readyState === WebSocket.OPEN) {
            var data = {
                type: "sendMessage",
                userId: userId,
                recipientId: recipientId,
                message: message
            };
            socket.send(JSON.stringify(data));

            // Não atualiza a interface imediatamente, pois a mensagem será recebida via WebSocket
            $("#message").val("");
        } else {
            console.error("A conexão WebSocket não está totalmente aberta.");
        }
    }

    $('#send').click(function () {
        var message = $("#message").val();

        if (userIdFromURL && !isNaN(userIdFromURL) && recipientIdFromURL && !isNaN(recipientIdFromURL)) {
            sendMessage(userIdFromURL, recipientIdFromURL, message);
        } else {
            console.error("IDs de usuário inválidos.");
        }
    });

    function loadUsers(userId) {
        $.ajax({
            type: 'GET',
            url: 'get_users.php',
            dataType: 'json',
            success: function (users) {
                // Limpa o conteúdo atual da barra lateral rolável
                $('.user-list-container').empty();

                // Adiciona os usuários à barra lateral rolável
                for (var i = 0; i < users.length; i++) {
                    var user = users[i];
                    var userElement = '<div class="user-list-item" data-user-id="' + user.id + '">' + user.nome + '</div>';
                    $('.user-list-container').append(userElement);
                }
            },
            error: function (error) {
                console.error('Erro ao carregar usuários: ' + error.responseText);
            }
        });
    }

    // Carrega os usuários ao iniciar a página
    loadUsers(userIdFromURL);
});
</script>


<?php
include('config.php');

$userIdFromURL = isset($_GET['ID']) ? $_GET['ID'] : null;

// Verifique se o ID do usuário na URL é válido
if ($userIdFromURL !== null) {

    // Consulta para buscar os IDs dos usuários com os quais houve interações na tabela de mensagens
    $sql = "SELECT DISTINCT remetente_id, destinatario_id FROM messages WHERE remetente_id = '$userIdFromURL' OR destinatario_id = '$userIdFromURL'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $userIds = array();

        while ($row = $result->fetch_assoc()) {
            if ($row['remetente_id'] != $userIdFromURL) {
                $userIds[] = $row['remetente_id'];
            }
            if ($row['destinatario_id'] != $userIdFromURL) {
                $userIds[] = $row['destinatario_id'];
            }
        }

        $userIds = array_unique($userIds);

        if (!empty($userIds)) {
            foreach ($userIds as $id) {
                // Utilize prepared statements para prevenir SQL injection
                $query = "SELECT nome FROM usuarios WHERE ID = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->bind_result($userName);

                if ($stmt->fetch()) {
                    echo '<div class="user">';
                    echo '<div class="user-list-item" data-user-id="' . $id . '">' . $userName . '</div>';
                    echo '<script>console.log("ID do Usuário: ' . $id . ', Nome do Usuário: ' . $userName . '");</script>';
                    echo '</div>';    
                }            

                $stmt->close();
            }
        } else {
        }

    } else {
    }

} else {
    echo "ID de usuário inválido na URL.";
    echo '<script>console.log("ID de usuário inválido na URL.");</script>';
}

?>

<div class="user-messages" id="user-list">
    <!-- Adiciona um contêiner para tornar a lista rolável -->
    <div class="user-list-container"></div>
</div>
            
<div id="chat"></div>
        <div class="input-container">
            <input type="text" id="message" placeholder="Digite sua mensagem...">
            <div id="send">Enviar</div>
        </div>
</div>

</body>
</html>