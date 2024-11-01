<?php
class Dashboard {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->getConnection();
    }

    public function getAlunos() {
        $query = $this->db->query("SELECT * FROM alunos");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
