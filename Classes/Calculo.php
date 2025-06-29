<?php
class Calculo {
    private $conn;
    private $table_name = "composicao_corporal";

    public $aluno_id;

    public function __construct($db) {
        $this->conn = $db;
    }

}
