<?php
session_start();
require_once '../Classes/Database.php'; // Inclua o arquivo de conexão com o banco de dados

// Ativa exibição de erros para debug (remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do POST
    $id_aluno = $_POST['id_aluno'] ?? null;
    $data_escolhida = $_POST['data_escolhida'] ?? null;
    $formula_escolhida = $_POST['formula_escolhida'] ?? null;
    $idade_aluno = $_POST['idade_aluno'] ?? null;
    $percentualGordura = $_POST['percentual_gordura'] ?? null;
    $massa_gorda = $_POST['massa_gorda'] ?? null;
    $massa_magra = $_POST['massa_magra'] ?? null;

    // Valida os dados recebidos
    if ($id_aluno && $data_escolhida && $formula_escolhida) {
        try {
            // Conecta ao banco de dados
            $db = new Database();
            $pdo = $db->getConnection();

            // Insere os dados na tabela
            $sql = "INSERT INTO composicao_corporal (id_aluno, data, formula, resultado_porcentagem, massa_gorda, massa_magra) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$id_aluno, $data_escolhida, $formula_escolhida, $percentualGordura, $massa_gorda, $massa_magra])) {
                echo json_encode(['success' => 'Dados salvos com sucesso!']);
            } else {
                echo json_encode(['error' => 'Erro ao salvar os dados no banco de dados.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erro PDO: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Dados incompletos ou inválidos.']);
    }
} else {
    echo json_encode(['error' => 'Método de requisição inválido.']);
}
