<?php
require_once '../Classes/Database.php';
require_once '../Classes/User.php';

class ResetSenhaController {
    private $user;
    private $token;
    private $email;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['reset_token']) || !isset($_SESSION['reset_email'])) {
            header('Location: ../views/forgot_password.php');
            exit;
        }

        $database = new Database();
        $this->user = new User($database);

        $this->token = $_SESSION['reset_token'];
        $this->email = $_SESSION['reset_email'];
    }

    public function validateToken() {
        return $this->user->validateToken($this->email, $this->token);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            $nova_senha = $_POST['nova_senha'];

            if ($this->user->updatePassword($this->email, $nova_senha)) {
                unset($_SESSION['reset_token']);
                unset($_SESSION['reset_email']);
                header('Location: ../index.php');
                exit;
            } else {
                return "Erro ao redefinir a senha. Tente novamente.";
            }
        }
        return null;
    }

    public function getEmail() {
        return $this->email;
    }
}

$controller = new ResetSenhaController();
$erro = $controller->handleRequest();
?>
