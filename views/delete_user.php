<?php
include '../Classes/User.php';
include '../Classes/Database.php';

// Verifica se o ID do usuário a ser excluído foi fornecido
if (isset($_GET['id'])) {
    $idUsuario = $_GET['id'];

    try {
        // Cria uma instância do UserManager
        $userManager = new User(new Database()); 

        // Chama o método deleteUser para excluir o usuário
        $userManager->deleteUser($idUsuario);
    } catch (Exception $e) {
        echo '<script>';
        echo 'alert("Erro ao excluir usuário: ' . $e->getMessage() . '");';
        echo 'window.location.href = "../dashboards/dashboard_usuarios.php";'; // Redireciona em caso de erro
        echo '</script>';
        exit;
    }
} else {
    echo '<script>';
    echo 'alert("ID do usuário não fornecido.");';
    echo 'window.location.href = "../dashboards/dashboard_usuarios.php";'; // Redireciona se o ID não foi fornecido
    echo '</script>';
    exit;
}
?>
