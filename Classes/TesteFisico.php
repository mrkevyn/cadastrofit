<?php
class TesteFisico {
    private $conn;
    private $table_name = "testes_fisicos";

    public $aluno_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function inserirTesteFisico($aluno_id, $banco_de_wells, $distancia_percorrida, $fc_max) {
        $query = "INSERT INTO " . $this->table_name . " (aluno_id, banco_de_wells, distancia_percorrida, fc_max) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$aluno_id, $banco_de_wells, $distancia_percorrida, $fc_max]);
    }
}
?>
