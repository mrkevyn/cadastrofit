<?php

class Anamnese {
    private $conn;
    private $table_name = 'anamnese';
    public $aluno_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Insert new anamnese
    public function insertAnamnese(
        $aluno_id, $objetivos, $nova_diabetes, $nova_cardiopatia, $nova_hipertensao, 
        $nova_outras_doencas, $nova_doencas_cronicas_outros_descricao, $novo_fumante, 
        $nova_bebidas_alcoolicas, $novo_exercicio_regular, $novo_exercicio_frequencia, 
        $novo_exercicio_tipo, $novo_medicamentos, $novo_medicamentos_descricao, 
        $nova_cirurgia, $nova_cirurgia_descricao, $novo_historico_diabetes, 
        $novo_historico_cardiopatia, $novo_historico_hipertensao, $novo_historico_cancer, 
        $novo_historico_outros, $novo_historico_descricao, $novo_problemas_osteoarticulares, 
        $novo_problemas_osteoarticulares_descricao
    ) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (aluno_id, objetivos, diabetes, cardiopatia, hipertensao, outras_doencas, doencas_cronicas_outros_descricao, fumante, bebidas_alcoolicas, exercicio_regular, exercicio_frequencia, exercicio_tipo, medicamentos, medicamentos_descricao, cirurgia, cirurgia_descricao, historico_diabetes, historico_cardiopatia, historico_hipertensao, historico_cancer, historico_outros, historico_descricao, problemas_osteoarticulares, problemas_osteoarticulares_descricao) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute([
                $aluno_id, $objetivos, $nova_diabetes, $nova_cardiopatia, $nova_hipertensao, 
                $nova_outras_doencas, $nova_doencas_cronicas_outros_descricao, $novo_fumante, 
                $nova_bebidas_alcoolicas, $novo_exercicio_regular, $novo_exercicio_frequencia, 
                $novo_exercicio_tipo, $novo_medicamentos, $novo_medicamentos_descricao, 
                $nova_cirurgia, $nova_cirurgia_descricao, $novo_historico_diabetes, 
                $novo_historico_cardiopatia, $novo_historico_hipertensao, $novo_historico_cancer, 
                $novo_historico_outros, $novo_historico_descricao, $novo_problemas_osteoarticulares, 
                $novo_problemas_osteoarticulares_descricao
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Erro ao inserir anamnese: " . $e->getMessage();
            return false;
        }
    }

    // Check existing objectives for a specific student
    public function checkExistingObjetivos($aluno_id) {
        $query = "SELECT objetivos FROM " . $this->table_name . " 
                  WHERE aluno_id = ? AND objetivos IS NOT NULL AND objetivos != ''";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$aluno_id]);
        return $stmt;
    }

}

?>
