<?php
session_start();
include '../Classes/Database.php';
include '../Classes/Antropometria.php';
include '../Classes/Aluno.php';

// Verifique se o ID do aluno é válido
$aluno_id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$aluno_id) {
    echo "ID do aluno inválido.";
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conecta ao banco de dados
try {
    $database = new Database();
    $conn = $database->getConnection();
} catch (Exception $e) {
    die("Falha na conexão com o banco de dados: " . $e->getMessage());
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
    $antropometria = new Antropometria($conn);

    // Dados do formulário
    $data = [
        'aluno_id' => $aluno_id,
        'peso' => !empty($_POST["novo_peso"]) ? floatval($_POST["novo_peso"]) : 0,
        'torax' => !empty($_POST["novo_torax"]) ? floatval($_POST["novo_torax"]) : 0,
        'estatura' => !empty($_POST["nova_estatura"]) ? floatval($_POST["nova_estatura"]) : 0,
        'cintura' => !empty($_POST["nova_cintura"]) ? floatval($_POST["nova_cintura"]) : 0,
        'abdomem' => !empty($_POST["novo_abdomem"]) ? floatval($_POST["novo_abdomem"]) : 0,
        'quadril' => !empty($_POST["novo_quadril"]) ? floatval($_POST["novo_quadril"]) : 0,
        'braco_relaxado_direito' => !empty($_POST["novo_braco_relaxado_direito"]) ? floatval($_POST["novo_braco_relaxado_direito"]) : 0,
        'braco_relaxado_esquerdo' => !empty($_POST["novo_braco_relaxado_esquerdo"]) ? floatval($_POST["novo_braco_relaxado_esquerdo"]) : 0,
        'braco_contraido_direito' => !empty($_POST["novo_braco_contraido_direito"]) ? floatval($_POST["novo_braco_contraido_direito"]) : 0,
        'braco_contraido_esquerdo' => !empty($_POST["novo_braco_contraido_esquerdo"]) ? floatval($_POST["novo_braco_contraido_esquerdo"]) : 0,
        'antebraco_direito' => !empty($_POST["novo_antebraco_direito"]) ? floatval($_POST["novo_antebraco_direito"]) : 0,
        'antebraco_esquerdo' => !empty($_POST["novo_antebraco_esquerdo"]) ? floatval($_POST["novo_antebraco_esquerdo"]) : 0,
        'coxa_proximal_direita' => !empty($_POST["nova_coxa_proximal_direita"]) ? floatval($_POST["nova_coxa_proximal_direita"]) : 0,
        'coxa_proximal_esquerda' => !empty($_POST["nova_coxa_proximal_esquerda"]) ? floatval($_POST["nova_coxa_proximal_esquerda"]) : 0,
        'perna_direita' => !empty($_POST["nova_perna_direita"]) ? floatval($_POST["nova_perna_direita"]) : 0,
        'perna_esquerda' => !empty($_POST["nova_perna_esquerda"]) ? floatval($_POST["nova_perna_esquerda"]) : 0,
        'subescapular' => !empty($_POST["nova_subescapular"]) ? floatval($_POST["nova_subescapular"]) : 0,
        'triceps' => !empty($_POST["novo_triceps"]) ? floatval($_POST["novo_triceps"]) : 0,
        'axilar_medial_vertical' => !empty($_POST["nova_axilar_medial_vertical"]) ? floatval($_POST["nova_axilar_medial_vertical"]) : 0,
        'biceps' => !empty($_POST["novo_biceps"]) ? floatval($_POST["novo_biceps"]) : 0,
        'supra_iliaca_anterior' => !empty($_POST["nova_supra_iliaca_anterior"]) ? floatval($_POST["nova_supra_iliaca_anterior"]) : 0,
        'coxa_proximal' => !empty($_POST["nova_coxa_proximal"]) ? floatval($_POST["nova_coxa_proximal"]) : 0,
        'supra_iliaca_medial' => !empty($_POST["nova_supra_iliaca_medial"]) ? floatval($_POST["nova_supra_iliaca_medial"]) : 0,
        'coxa_medial' => !empty($_POST["nova_coxa_medial"]) ? floatval($_POST["nova_coxa_medial"]) : 0,
        'peitoral' => !empty($_POST["novo_peitoral"]) ? floatval($_POST["novo_peitoral"]) : 0,
        'perna' => !empty($_POST["nova_perna"]) ? floatval($_POST["nova_perna"]) : 0,
        'abdominal_vertical' => !empty($_POST["novo_abdominal_vertical"]) ? floatval($_POST["novo_abdominal_vertical"]) : 0,
        'biestiloide' => !empty($_POST["novo_biestiloide"]) ? floatval($_POST["novo_biestiloide"]) : 0,
        'biependicondilar_umeral' => !empty($_POST["novo_biependicondilar_umeral"]) ? floatval($_POST["novo_biependicondilar_umeral"]) : 0,
        'biependicondilar_femural' => !empty($_POST["novo_biependicondilar_femural"]) ? floatval($_POST["novo_biependicondilar_femural"]) : 0,
    ];

    // Calcular IMC e ICQ
    $data['imc'] = $antropometria->calcularIMC($data['peso'], $data['estatura']);
    $data['icq'] = $antropometria->calcularICQ($data['cintura'], $data['quadril']);

    if ($antropometria->inserirAntropometria($data)) {
        $_SESSION['aluno_id'] = $aluno_id;
        $_SESSION['aluno_nome'] = $aluno['nome'];

        // Redireciona para a página de detalhes do aluno
        header("Location: ../public/detalhes_aluno.php?id=$aluno_id");
        exit;
    } else {
        echo "Erro ao inserir dados de antropometria.";
    }
}
?>
