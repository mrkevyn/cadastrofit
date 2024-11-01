<?php
class Calculo {
    private $conn;
    private $table_name = "calculadora";

    public $aluno_id;

    public function __construct($db) {
        $this->conn = $db;
    }

}
