<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../Classes/Database.php';
require_once '../Classes/Aluno.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $aluno = new Aluno($db);

    $nome = $_POST['nome'];
    $dataNascimento = $_POST['dataNascimento'];
    $telefone = $_POST['telefone'];
    $sexo = $_POST['sexo'];
    $email = $_POST['email'];

    if ($aluno->emailExists($email)) {
        $_SESSION['error_message'] = "O email já está cadastrado. Por favor, use um email diferente.";
        header("Location: ../forms/cadastroAluno.php");
        exit();
    } else {
        $aluno_id = $aluno->cadastrarAluno($nome, $dataNascimento, $telefone, $sexo, $email);
        $_SESSION['aluno_id'] = $aluno_id;
        header("Location: ../sucesso/cadastroSucesso.html");
        exit();
    }
}
?>
