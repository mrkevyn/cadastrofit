<?php
class TesteFisico {
    private $conn;
    private $table_name = "testes_fisicos";

    public $aluno_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function inserirTesteFisico($aluno_id, $banco_de_wells, $distancia_percorrida, $fc_max) {
        $vo2_maximo = $this->calcularVO2Maximo($distancia_percorrida); // Calculando o VO2 Máximo

        $query = "INSERT INTO " . $this->table_name . " (aluno_id, banco_de_wells, distancia_percorrida, fc_max, vo2_maximo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$aluno_id, $banco_de_wells, $distancia_percorrida, $fc_max, $vo2_maximo]); // Inserindo o VO2 Máximo
    }

    // Método para calcular o VO2 Máximo
    private function calcularVO2Maximo($distancia_percorrida) {
        return (0.0268 * $distancia_percorrida) - 11.3;
    }

    public function obterVO2Maximo($aluno_id, $data_escolhida) {
        // Modifica a consulta para filtrar pelo aluno_id e pela data
        $query = "SELECT vo2_maximo FROM " . $this->table_name . " WHERE aluno_id = ? AND DATE(created_at) = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$aluno_id, $data_escolhida]);
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['vo2_maximo'];  // Retorna o valor de vo2_maximo
        } else {
            return null;  // Retorna null se nenhum registro for encontrado
        }
    }
    
}
?>
