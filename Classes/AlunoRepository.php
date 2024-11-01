<?php
class AlunoRepository {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->getConnection();
    }

    public function getDatasAnamnese($alunoId) {
        $stmt = $this->db->prepare("SELECT DISTINCT DATE(created_at) AS data FROM anamnese WHERE aluno_id = ? ORDER BY data DESC");
        $stmt->execute([$alunoId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAnamnese($alunoId, $dataEscolhida = null) {
        $sql = "SELECT * FROM anamnese WHERE aluno_id = ?";
        $params = [$alunoId];

        if ($dataEscolhida) {
            $sql .= " AND DATE(created_at) = ?";
            $params[] = $dataEscolhida;
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDatasAntropometria($alunoId) {
        $stmt = $this->db->prepare("SELECT DISTINCT DATE(created_at) AS data FROM antropometria WHERE aluno_id = ? ORDER BY data DESC");
        $stmt->execute([$alunoId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAntropometria($alunoId, $dataEscolhida = null) {
        $sql = "SELECT * FROM antropometria WHERE aluno_id = ?";
        $params = [$alunoId];

        if ($dataEscolhida) {
            $sql .= " AND DATE(created_at) = ?";
            $params[] = $dataEscolhida;
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTestesFisicos($alunoId, $dataEscolhida) {
        if (!$dataEscolhida) {
            echo "Erro: dataEscolhida não foi fornecida.\n";
            return null;
        }
    
        $sql = "SELECT * FROM testes_fisicos WHERE aluno_id = ? AND DATE(created_at) = ?";
        $stmt = $this->db->prepare($sql);
    
        // Adicionando instrução de depuração
        echo "Query: $sql\n";
        echo "Parameters: alunoId = $alunoId, dataEscolhida = $dataEscolhida\n";
    
        $stmt->execute([$alunoId, $dataEscolhida]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getCalculos($alunoId, $dataEscolhida) {
        if (!$dataEscolhida) {
            echo "Erro: dataEscolhida não foi fornecida.\n";
            return [];
        }
    
        // Primeira consulta: tenta buscar dados para a data escolhida
        $sql = "SELECT * FROM calculadora WHERE id_aluno = ? AND DATE(created_at) = ?";
        $params = [$alunoId, $dataEscolhida];
        $stmt = $this->db->prepare($sql);
    
        // Adicionando instrução de depuração
        echo "Primeira consulta: $sql\n";
        echo "Parameters: alunoId = $alunoId, dataEscolhida = $dataEscolhida\n";
    
        if (!$stmt) {
            $errorInfo = $this->db->errorInfo();
            echo "Erro ao preparar a consulta: " . $errorInfo[2] . "\n";
            return [];
        }
    
        $stmt->execute($params);
        
        if ($stmt->errorCode() != '00000') {
            $errorInfo = $stmt->errorInfo();
            echo "Erro ao executar a consulta: " . $errorInfo[2] . "\n";
            return [];
        }
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Adicionando instrução de depuração
        echo "Resultado da primeira consulta: ";
        print_r($result);
    
        // Se encontrou resultados, retorna-os
        if (!empty($result)) {
            return $result;
        }
    
        // Segunda consulta: tenta buscar dados na próxima data disponível
        $sql = "SELECT * FROM calculadora WHERE id_aluno = ? AND DATE(created_at) > ? ORDER BY DATE(created_at) ASC LIMIT 1";
        $params = [$alunoId, $dataEscolhida];
        $stmt = $this->db->prepare($sql);
    
        // Adicionando instrução de depuração
        echo "Segunda consulta: $sql\n";
        echo "Parameters: alunoId = $alunoId, dataEscolhida = $dataEscolhida\n";
    
        if (!$stmt) {
            $errorInfo = $this->db->errorInfo();
            echo "Erro ao preparar a consulta: " . $errorInfo[2] . "\n";
            return [];
        }
    
        $stmt->execute($params);
    
        if ($stmt->errorCode() != '00000') {
            $errorInfo = $stmt->errorInfo();
            echo "Erro ao executar a consulta: " . $errorInfo[2] . "\n";
            return [];
        }
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Adicionando instrução de depuração
        echo "Resultado da segunda consulta: ";
        print_r($result);
    
        return $result;
    }
     
    
    public function getObjetivos($alunoId) {
        $stmt = $this->db->prepare("SELECT objetivos FROM anamnese WHERE aluno_id = ? AND objetivos IS NOT NULL AND objetivos != '' ORDER BY created_at ASC LIMIT 1");
        $stmt->execute([$alunoId]);
        $anamneseObj = $stmt->fetch(PDO::FETCH_ASSOC);
        return $anamneseObj['objetivos'] ?? 'N/A';
    }

    public function getAluno($id_aluno) {
        $stmt = $this->db->prepare("SELECT * FROM alunos WHERE id = ?");
        $stmt->execute([$id_aluno]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllAnamnese($id_aluno) {
        $stmt = $this->db->prepare("SELECT * FROM anamnese WHERE aluno_id = ?");
        $stmt->execute([$id_aluno]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllAntropometria($id_aluno) {
        $stmt = $this->db->prepare("SELECT * FROM antropometria WHERE aluno_id = ?");
        $stmt->execute([$id_aluno]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTestesFisicos($id_aluno) {
        $stmt = $this->db->prepare("SELECT * FROM testes_fisicos WHERE aluno_id = ?");
        $stmt->execute([$id_aluno]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCalculos($id_aluno) {
        $stmt = $this->db->prepare("SELECT * FROM calculadora WHERE id_aluno = ?");
        $stmt->execute([$id_aluno]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
