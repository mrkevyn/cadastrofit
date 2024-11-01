<?php
session_start();

$id_aluno = $_SESSION['aluno_id'] ?? null;

if (!$id_aluno) {
    header("Location: dashboard.php");
    exit;
}

require_once '../classes/Database.php';
require_once '../classes/AlunoRepository.php';

// Crie uma instância da classe Database
$database = new Database();

// Passe a instância Database para o repositório
$alunoRepo = new AlunoRepository($database);

// Depuração: Verificar se a conexão com o banco de dados foi bem-sucedida
try {
    $dbConnection = $database->getConnection();
    echo "Conexão com o banco de dados estabelecida com sucesso.<br>";
} catch (Exception $e) {
    echo "Erro ao conectar com o banco de dados: " . $e->getMessage();
    exit;
}

// Recuperar dados do aluno
$aluno = $alunoRepo->getAluno($id_aluno);
$anamneses = $alunoRepo->getAllAnamnese($id_aluno);
$antropometrias = $alunoRepo->getAllAntropometria($id_aluno);
$testesFisicos = $alunoRepo->getAllTestesFisicos($id_aluno);
$calculos = $alunoRepo->getAllCalculos($id_aluno);

// Depuração: Verificar se os dados foram recuperados corretamente
echo '<pre>';
echo "Dados do aluno:<br>";
print_r($aluno);
echo "Anamneses:<br>";
print_r($anamneses);
echo "Antropometrias:<br>";
print_r($antropometrias);
echo "Testes Físicos:<br>";
print_r($testesFisicos);
echo "Cálculos:<br>";
print_r($calculos);
echo '</pre>';

$_SESSION['aluno'] = $aluno;
$_SESSION['anamneses'] = $anamneses;
$_SESSION['antropometrias'] = $antropometrias;
$_SESSION['testesFisicos'] = $testesFisicos;
$_SESSION['calculos'] = $calculos;

?>
