<?php
session_start();
include '../Classes/Database.php';
include '../Classes/TesteFisico.php';
include '../Classes/Aluno.php';

$database = new Database();
$db = $database->getConnection();

$aluno_id = isset($_GET['id']) ? $_GET['id'] : null;

// Verifique se o ID do aluno é válido
if (!$aluno_id) {
    echo "ID do aluno inválido.";
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cria instâncias das classes
$alunoObj = new Aluno($database);

// Obtém os detalhes do aluno pelo ID
$aluno = $alunoObj->getAlunoDetails($aluno_id);
if (!$aluno) {
    echo "Aluno não encontrado.";
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $testeFisicoObj = new TesteFisico($db);

    $novo_banco_de_wells = $_POST["novo_banco_de_wells"];
    $nova_distancia_percorrida = $_POST["nova_distancia_percorrida"];
    $nova_fc_max = $_POST["nova_fc_max"];

    // Insere os dados do novo teste físico
    $testeFisicoObj->inserirTesteFisico($aluno_id, $novo_banco_de_wells, $nova_distancia_percorrida, $nova_fc_max);

    $_SESSION['aluno_id'] = $aluno_id; // Armazene o ID na sessão

    header("Location: ../public/detalhes_aluno.php?id=$aluno_id");
    exit;
}

// Inclui o arquivo de template HTML
include 'novoTesteFisicoForm.php';
?>
