<?php
class Database {
    private $host = 'localhost';
    private $db = 'efitnessdb';
    private $user = 'postgres';
    private $pass = '080899';
    private $charset = 'utf8';
    private $pdo;

    public function __construct() {
        $dsn = "pgsql:host=$this->host;dbname=$this->db;options='--client_encoding=$this->charset'";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            throw new Exception("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>
