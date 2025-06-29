<?php
session_start();
include '../Classes/Database.php';
include '../Classes/Anamnese.php';
include '../Classes/Aluno.php';

$database = new Database();
$db = $database->getConnection();
$alunoObj = new Aluno(new Database());

// Verifica se o ID do aluno é válido
$aluno_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$aluno_id) {
    echo "ID do aluno inválido.";
    exit;
}

// Obtém os detalhes do aluno pelo ID
$aluno = $alunoObj->getAlunoDetails($aluno_id);
if (!$aluno) {
    echo "Aluno não encontrado.";
    exit;
}

$anamnese = new Anamnese($db);

// Processa o formulário quando enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe e valida os dados do formulário
    $objetivos = isset($_POST["objetivos"]) ? $_POST["objetivos"] : null;
    $nova_diabetes = isset($_POST["nova_diabetes"]) ? $_POST["nova_diabetes"] : null;
    $nova_cardiopatia = isset($_POST["nova_cardiopatia"]) ? $_POST["nova_cardiopatia"] : null;
    $nova_hipertensao = isset($_POST["nova_hipertensao"]) ? $_POST["nova_hipertensao"] : null;
    $nova_outras_doencas = isset($_POST['nova_outras_doencas']) ? $_POST['nova_outras_doencas'] : null;
    $nova_doencas_cronicas_outros_descricao = isset($_POST['nova_doencas_cronicas_outros_descricao']) ? $_POST['nova_doencas_cronicas_outros_descricao'] : null;
    $novo_fumante = isset($_POST['novo_fumante']) ? $_POST['novo_fumante'] : null;
    $nova_bebidas_alcoolicas = isset($_POST['nova_bebidas_alcoolicas']) ? $_POST['nova_bebidas_alcoolicas'] : null;
    $novo_exercicio_regular = isset($_POST['novo_exercicio_regular']) ? $_POST['novo_exercicio_regular'] : null;
    $novo_exercicio_frequencia = isset($_POST['novo_exercicio_frequencia']) ? $_POST['novo_exercicio_frequencia'] : null;
    $novo_exercicio_tipo = isset($_POST['novo_exercicio_tipo']) ? $_POST['novo_exercicio_tipo'] : null;
    $novo_medicamentos = isset($_POST['novo_medicamentos']) ? $_POST['novo_medicamentos'] : null;
    $novo_medicamentos_descricao = isset($_POST['novo_medicamentos_descricao']) ? $_POST['novo_medicamentos_descricao'] : null;
    $nova_cirurgia = isset($_POST['nova_cirurgia']) ? $_POST['nova_cirurgia'] : null;
    $nova_cirurgia_descricao = isset($_POST['nova_cirurgia_descricao']) ? $_POST['nova_cirurgia_descricao'] : null;
    $novo_historico_diabetes = isset($_POST['novo_historico_diabetes']) ? $_POST['novo_historico_diabetes'] : null;
    $novo_historico_cardiopatia = isset($_POST['novo_historico_cardiopatia']) ? $_POST['novo_historico_cardiopatia'] : null;
    $novo_historico_hipertensao = isset($_POST['novo_historico_hipertensao']) ? $_POST['novo_historico_hipertensao'] : null;
    $novo_historico_cancer = isset($_POST['novo_historico_cancer']) ? $_POST['novo_historico_cancer'] : null;
    $novo_historico_outros = isset($_POST['novo_historico_outros']) ? $_POST['novo_historico_outros'] : null;
    $novo_historico_descricao = isset($_POST['novo_historico_descricao']) ? $_POST['novo_historico_descricao'] : null;
    $novo_problemas_osteoarticulares = isset($_POST['novo_problemas_osteoarticulares']) ? $_POST['novo_problemas_osteoarticulares'] : null;
    $novo_problemas_osteoarticulares_descricao = isset($_POST['novo_problemas_osteoarticulares_descricao']) ? $_POST['novo_problemas_osteoarticulares_descricao'] : null;

    // Insere nova anamnese
    if ($anamnese->insertAnamnese(
        $aluno_id, $objetivos, $nova_diabetes, $nova_cardiopatia, $nova_hipertensao, 
        $nova_outras_doencas, $nova_doencas_cronicas_outros_descricao, $novo_fumante, 
        $nova_bebidas_alcoolicas, $novo_exercicio_regular, $novo_exercicio_frequencia, 
        $novo_exercicio_tipo, $novo_medicamentos, $novo_medicamentos_descricao, 
        $nova_cirurgia, $nova_cirurgia_descricao, $novo_historico_diabetes, 
        $novo_historico_cardiopatia, $novo_historico_hipertensao, $novo_historico_cancer, 
        $novo_historico_outros, $novo_historico_descricao, $novo_problemas_osteoarticulares, 
        $novo_problemas_osteoarticulares_descricao
    )) {
        // Redireciona para a página de detalhes do aluno
        $_SESSION['aluno_id'] = $aluno_id;
        $_SESSION['aluno_nome'] = $aluno['nome'];
        header("Location: ../public/detalhes_aluno.php?id=$aluno_id");
        exit;
    } else {
        echo "Falha ao inserir anamnese.";
    }
}

// Consulta para verificar os objetivos existentes
$stmtObjetivos = $anamnese->checkExistingObjetivos($aluno_id);
$anamneseObjetivos = $stmtObjetivos->fetch(PDO::FETCH_ASSOC);
$objetivos_cadastrados = $anamneseObjetivos ? $anamneseObjetivos['objetivos'] : null;

include 'novaAnamnese.php';
?>