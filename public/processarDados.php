<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../Classes/Database.php';
include '../Classes/AlunoRepository.php';
include '../Classes/Aluno.php';
include '../Classes/Formulas.php';

$db = new Database();
$alunoRepo = new AlunoRepository($db);
$alunoClass = new Aluno($db);
$formulasClass = new Formulas();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aluno = isset($_POST['id_aluno']) ? $_POST['id_aluno'] : null;
    $data_escolhida = isset($_POST['data_escolhida']) ? $_POST['data_escolhida'] : null;
    $formula_escolhida = isset($_POST['formula_escolhida']) ? $_POST['formula_escolhida'] : null;
    $idade_aluno = isset($_POST['idade_aluno']) ? $_POST['idade_aluno'] : null;

    if (!$id_aluno || !$data_escolhida || !$formula_escolhida || !$idade_aluno) {
        echo json_encode([
            'error' => 'Dados incompletos.',
            'id_aluno' => $id_aluno,
            'data_escolhida' => $data_escolhida,
            'formula_escolhida' => $formula_escolhida,
            'idade_aluno' => $idade_aluno
        ]);
        exit;
    }

    $antropometria = $alunoRepo->getAntropometria($id_aluno, $data_escolhida);
    $resultado = null;

    if ($formula_escolhida === 'percentual_gordura_masculina') {
        $resultado = $formulasClass->calcularPercentualGorduraMasculina($antropometria);
    } elseif ($formula_escolhida === 'percentual_gordura_feminina') {
        $resultado = $formulasClass->calcularPercentualGorduraFeminina($antropometria);
    } elseif ($formula_escolhida === 'percentual_gordura_meninos') {
        $resultado = $formulasClass->calcularPercentualGorduraMeninos($antropometria, $idade_aluno);
    } elseif ($formula_escolhida === 'percentual_gordura_meninas') {
        $resultado = $formulasClass->calcularPercentualGorduraMeninas($antropometria, $idade_aluno);
    }

    if ($resultado !== null) {
        $query = "INSERT INTO calculadora (id_aluno, formula, data, resultado_porcentagem) VALUES (:id_aluno, :formula, :data, :resultado_porcentagem)";
        $stmt = $db->getConnection()->prepare($query);
        $stmt->bindParam(':id_aluno', $id_aluno);
        $stmt->bindParam(':data', $data_escolhida);
        $stmt->bindParam(':formula', $formula_escolhida);
        $stmt->bindParam(':resultado_porcentagem', $resultado);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Dados salvos com sucesso.']);
        } else {
            echo json_encode(['error' => 'Erro ao salvar os dados.', 'db_error' => $stmt->errorInfo()]);
        }
    } else {
        echo json_encode(['error' => 'Erro ao calcular os resultados.']);
    }
}
?>
