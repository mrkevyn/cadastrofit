<?php
class Aluno {
    private $db;
    private $aluno_id;
    private $aluno_nome;

    public $nome;
    public $sexo;
    public $telefone;

    public function __construct(Database $db) {
        $this->db = $db->getConnection();
    }

    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id FROM alunos WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn();
    }

    public function cadastrarAluno($nome, $dataNascimento, $telefone, $sexo, $email) {
        $stmt = $this->db->prepare("INSERT INTO alunos (nome, data_nascimento, telefone, sexo, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $dataNascimento, $telefone, $sexo, $email]);

        $stmt = $this->db->prepare("SELECT id FROM alunos WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn();
    }

    public function getAlunoDetails($aluno_id) {
        $stmt = $this->db->prepare("SELECT * FROM alunos WHERE id = ?");
        $stmt->execute([$aluno_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getIdade($dataNascimento) {
        $data_nascimento = new DateTime($dataNascimento);
        $data_atual = new DateTime();
        return $data_atual->diff($data_nascimento)->y;
    }

    public function getSexo($aluno_id) {
    $stmt = $this->db->prepare("SELECT sexo FROM alunos WHERE id = ?");
    $stmt->execute([$aluno_id]);
    return $stmt->fetchColumn(); // Retorna 'masculino' ou 'feminino'
    }
    
    public function excluirAluno($aluno_id) {
        try {
            $this->db->beginTransaction();

            $stmt1 = $this->db->prepare("DELETE FROM testes_fisicos WHERE aluno_id = ?");
            $stmt2 = $this->db->prepare("DELETE FROM antropometria WHERE aluno_id = ?");
            $stmt3 = $this->db->prepare("DELETE FROM anamnese WHERE aluno_id = ?");
            $stmt4 = $this->db->prepare("DELETE FROM alunos WHERE id = ?");

            $stmt1->execute([$aluno_id]);
            $stmt2->execute([$aluno_id]);
            $stmt3->execute([$aluno_id]);
            $stmt4->execute([$aluno_id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

}
?>