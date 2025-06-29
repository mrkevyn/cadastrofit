<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    include '../Classes/Database.php';
    include '../Classes/Aluno.php';

    $database = new Database();
    $db = $database->getConnection();
    $alunoObj = new Aluno($database);

    $aluno_id = $_GET["id"];

    try {
        $alunoObj->excluirAluno($aluno_id);
        header("Location: ../dashboards/dashboard_alunos.php?id=" . $_SESSION['usuario_id']);
        exit();
    } catch (Exception $e) {
        echo "Erro ao excluir aluno: " . $e->getMessage();
    }
} else {
    header("Location: ../dashboards/dashboard_alunos.php?id=" . $_SESSION['usuario_id']);
    exit();
}
?>
