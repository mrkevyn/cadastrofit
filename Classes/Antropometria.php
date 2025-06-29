<?php
class Antropometria {
    private $conn;

    private $table_name = "antropometria";

    public $aluno_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Função para calcular o IMC
    public function calcularIMC($peso, $estatura_cm) {
        if (!is_numeric($peso) || !is_numeric($estatura_cm) || $peso <= 0 || $estatura_cm <= 0) {
            return null;
        }
        $estatura = $estatura_cm / 100;
        $imc = $peso / ($estatura * $estatura);
        return number_format($imc, 2, '.', '');
    }

    // Função para calcular o ICQ
    public function calcularICQ($cintura, $quadril) {
        if (!is_numeric($cintura) || !is_numeric($quadril) || $quadril <= 0) {
            return null;
        }
        return $cintura / $quadril;
    }

    // Função para inserir nova antropometria
    public function inserirAntropometria($data) {
        $query = "INSERT INTO antropometria (aluno_id, peso, torax, estatura, cintura, abdomem, quadril, 
                    braco_relaxado_direito, braco_relaxado_esquerdo, braco_contraido_direito, 
                    braco_contraido_esquerdo, antebraco_direito, antebraco_esquerdo, coxa_proximal_direita, 
                    coxa_proximal_esquerda, perna_direita, perna_esquerda, subescapular, triceps, 
                    axilar_medial_vertical, biceps, supra_iliaca_anterior, coxa_proximal, supra_iliaca_medial, 
                    coxa_medial, peitoral, perna, abdominal_vertical, biestiloide, biependicondilar_umeral, 
                    biependicondilar_femural, imc, icq) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        try {
            $stmt->execute([
                $data['aluno_id'], $data['peso'], $data['torax'], $data['estatura'], $data['cintura'], 
                $data['abdomem'], $data['quadril'], $data['braco_relaxado_direito'], $data['braco_relaxado_esquerdo'], 
                $data['braco_contraido_direito'], $data['braco_contraido_esquerdo'], $data['antebraco_direito'], 
                $data['antebraco_esquerdo'], $data['coxa_proximal_direita'], $data['coxa_proximal_esquerda'], 
                $data['perna_direita'], $data['perna_esquerda'], $data['subescapular'], $data['triceps'], 
                $data['axilar_medial_vertical'], $data['biceps'], $data['supra_iliaca_anterior'], $data['coxa_proximal'], 
                $data['supra_iliaca_medial'], $data['coxa_medial'], $data['peitoral'], $data['perna'], 
                $data['abdominal_vertical'], $data['biestiloide'], $data['biependicondilar_umeral'], 
                $data['biependicondilar_femural'], $data['imc'], $data['icq']
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Erro ao inserir antropometria: " . $e->getMessage();
            return false;
        }
    }
}
?>
