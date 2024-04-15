<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nova conexão! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if ($data['type'] === 'sendMessage') {
            $userId = $data['userId'];
            $recipientId = $data['recipientId'];
            $message = $data['message'];

            // Adapte para suas configurações e necessidades específicas
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "site";

            // Conectar ao banco de dados
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar a conexão
            if ($conn->connect_error) {
                die("Conexão com o banco de dados falhou: " . $conn->connect_error);
            }

            // Utilizar prepared statements para evitar SQL injection
            $stmt = $conn->prepare("INSERT INTO messages (remetente_id, destinatario_id, conteudo, data_hora_envio) VALUES (?, ?, ?, NOW())");

            if ($stmt) {
                $stmt->bind_param("iis", $userId, $recipientId, $message);

                if ($stmt->execute()) {
                    echo "Mensagem inserida com sucesso na tabela\n";
                } else {
                    echo "Erro ao inserir mensagem na tabela: " . $stmt->error . "\n";
                }

                $stmt->close();
            } else {
                echo "Erro ao preparar a declaração: " . $conn->error . "\n";
            }

            $conn->close();
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Conexão {$conn->resourceId} fechada\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Erro: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Configurar o servidor WebSocket
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080 // Use a porta desejada
);

$server->run();
