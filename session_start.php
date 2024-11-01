<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'Classes/DatabaseConnection.php';
include 'Classes/User.php';
include 'Classes/UserRepository.php';

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$database = new DatabaseConnection();
$conn = $database->getConnection();
$userRepo = new UserRepository($conn);

// Verifica se a chave 'isAdmin' está definida na sessão
if (!isset($_SESSION['isadmin'])) {
    $_SESSION['isadmin'] = false;

    if (isset($_SESSION['isadmin']) && $_SESSION['isadmin']) {
        $adminInfo = $userRepo->getUserById($_SESSION['usuario_id']);
        $_SESSION['isadmin'] = $adminInfo['isadmin'];
        $_SESSION['nome_admin'] = $adminInfo['nome'];
    } else {
        header('Location: index.php');
        exit;
    }
}

// Consulta o banco de dados para obter os dados dos usuários
$usuarios = $userRepo->getAllUsers();
$_SESSION['usuarios'] = $usuarios;
?>
