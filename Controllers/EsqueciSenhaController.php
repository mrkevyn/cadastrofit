<?php
require_once '../Classes/Database.php';
require_once '../Classes/User.php';

class EsqueciSenhaController {
    private $user;

    public function __construct() {
        $database = new Database();
        $this->user = new User($database);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            $email = $_POST['email'];

            $usuario = $this->user->getUserByEmail($email);

            if ($usuario) {
                $token = bin2hex(random_bytes(32));
                $expiration = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $this->user->setTokenAndExpiration($email, $token, $expiration);

                $_SESSION['reset_token'] = $token;
                $_SESSION['reset_email'] = $email;

                header('Location: ../views/reset_password.php');
                exit;
            } else {
                return "E-mail não encontrado. Tente novamente.";
            }
        }
        return null;
    }
}

$controller = new EsqueciSenhaController();
$erro = $controller->handleRequest();
?>
