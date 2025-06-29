<?php
require_once '../Classes/AlunoRepository.php';
require_once '../Classes/Aluno.php';
require_once '../Classes/Formulas.php';
require_once '../Classes/Database.php';
require_once '../Classes/TesteFisico.php';

header('Content-Type: application/json');

class CalcularController {
    private $alunoRepository;
    private $aluno;
    private $formulas;
    private $testeFisico;

    public function __construct() {
        $database = new Database();
        $this->alunoRepository = new AlunoRepository($database);
        $this->aluno = new Aluno($database);
        $this->formulas = new Formulas();
        $this->testeFisico = new TesteFisico($database->getConnection());
    }

    public function processarCalculo($id_aluno, $formula, $data_escolhida) {
        if (isset($_GET['id']) && isset($_GET['formula'])) {
            $idAluno = $_GET['id'];
            $formula = $_GET['formula'];
            $dataEscolhida = $_GET['data'];  // Pega a data da requisição GET
    
            if ($formula === 'VO2_maximo') {
                // Obter VO2 Máximo considerando a data
                $vo2Maximo = $this->testeFisico->obterVO2Maximo($idAluno, $dataEscolhida);
    
                if ($vo2Maximo !== null) {
                    echo json_encode(['VO2_maximo' => $vo2Maximo]);
                    return;  // Finaliza o processo se o VO2 máximo for encontrado
                } else {
                    echo json_encode(['error' => 'VO2 Máximo não encontrado.']);
                    return;  // Finaliza o processo caso não encontre o VO2 máximo
                }
            }
        }    

        if (!$this->validarEntradas($id_aluno, $formula, $data_escolhida)) {
            return $this->responderErro("Dados de entrada inválidos.");
        }

        $antropometria = $this->alunoRepository->getAntropometria($id_aluno, $data_escolhida);
        if (!$antropometria) {
            return $this->responderErro("Nenhuma antropometria encontrada para o aluno e data especificados.");
        }

        $dados_aluno = $this->aluno->getAlunoDetails($id_aluno);
        if (!$this->validarDadosAluno($dados_aluno)) {
            return $this->responderErro("Dados do aluno incompletos.");
        }

        $idade = $this->aluno->getIdade($dados_aluno['data_nascimento']);
        $sexo = $dados_aluno['sexo']; // Verificar o sexo do aluno

        // Verifica qual fórmula usar com base no sexo e idade
        $resultado = $this->calcularFormula($formula, $idade, $antropometria, $sexo);

        return $this->formatarResposta($resultado, $formula);
    }

    private function validarEntradas($id_aluno, $formula, $data_escolhida) {
        return $id_aluno && $formula && $data_escolhida;
    }

    private function validarDadosAluno($dados_aluno) {
        return isset($dados_aluno['data_nascimento'], $dados_aluno['sexo']);
    }

    private function responderErro($mensagem) {
        return json_encode(["error" => $mensagem]);
    }

    private function calcularFormula($formula, $idade, $antropometria, $sexo) {
        switch ($formula) {
            case 'percentual_gordura_masculina':
                return $this->calcularPercentualGorduraMasculina($idade, $antropometria);
            case 'percentual_gordura_feminina':
                return $this->calcularPercentualGorduraFeminina($idade, $antropometria);
            case 'percentual_gordura_meninos':
                return $this->calcularPercentualGorduraMeninos($idade, $antropometria);
            case 'percentual_gordura_meninas':
                return $this->calcularPercentualGorduraMeninas($idade, $antropometria);
            default:
                return ["error" => "Fórmula desconhecida."];
        }
    }

    private function formatarResposta($resultado, $formula) {
        if (is_array($resultado)) {
            return json_encode($resultado);
        } elseif (isset($resultado["error"])) {
            return json_encode($resultado);
        } elseif (is_numeric($resultado)) {
            return json_encode([$formula => number_format($resultado, 2)]);
        } else {
            return $this->responderErro("Erro desconhecido ao processar o cálculo.");
        }
    }

    // Cálculos para masculino (adulto)
    private function calcularPercentualGorduraMasculina($idade, $antropometria) {
        if (empty($antropometria['peso'])) {
            return ["error" => "Peso não disponível."];
        }

        // Verifica se há dados para o cálculo da densidade corporal masculina
        $densidade_corporal = $this->formulas->calcularDensidadeCorporalMasculina($antropometria, $idade);
        if (!is_numeric($densidade_corporal)) {
            return ["error" => $densidade_corporal];
        }

        // Calcula o percentual de gordura
        $percentual_gordura = $this->formulas->calcularPercentualGorduraCorporal($densidade_corporal);
        $massa_gorda = $this->formulas->calcularMassaGorda($antropometria, $percentual_gordura);
        $massa_magra = $this->formulas->calcularMassaMagra($antropometria, $massa_gorda);

        return [
            'percentual_gordura_masculina' => number_format($percentual_gordura, 2),
            'massa_gorda' => number_format($massa_gorda, 2),
            'massa_magra' => number_format($massa_magra, 2),
            'peso' => number_format($antropometria['peso'], 2),
        ];
    }

    // Cálculos para feminino (adulto)
    private function calcularPercentualGorduraFeminina($idade, $antropometria) {
        if (empty($antropometria['peso'])) {
            return ["error" => "Peso não disponível."];
        }

        // Verifica se há dados para o cálculo da densidade corporal feminina
        $densidade_corporal = $this->formulas->calcularDensidadeCorporalFeminina($antropometria, $idade);
        if (!is_numeric($densidade_corporal)) {
            return ["error" => $densidade_corporal];
        }

        // Calcula o percentual de gordura
        $percentual_gordura = $this->formulas->calcularPercentualGorduraCorporal($densidade_corporal);
        $massa_gorda = $this->formulas->calcularMassaGorda($antropometria, $percentual_gordura);
        $massa_magra = $this->formulas->calcularMassaMagra($antropometria, $massa_gorda);

        return [
            'percentual_gordura_feminina' => number_format($percentual_gordura, 2),
            'massa_gorda' => number_format($massa_gorda, 2),
            'massa_magra' => number_format($massa_magra, 2),
            'peso' => number_format($antropometria['peso'], 2),
        ];
    }

    // Cálculos para meninos (faixa etária 8-18)
    private function calcularPercentualGorduraMeninos($idade, $antropometria) {
        if (empty($antropometria['peso'])) {
            return ["error" => "Peso não disponível."];
        }

        if ($idade >= 8 && $idade <= 18) {
            $percentual_gordura = $this->formulas->calcularPercentualGorduraMeninos($antropometria);
            $massa_gorda = $this->formulas->calcularMassaGorda($antropometria, $percentual_gordura);
            $massa_magra = $this->formulas->calcularMassaMagra($antropometria, $massa_gorda);

            return [
                'percentual_gordura_meninos' => number_format($percentual_gordura, 2),
                'massa_gorda' => number_format($massa_gorda, 2),
                'massa_magra' => number_format($massa_magra, 2),
                'peso' => number_format($antropometria['peso'], 2),
            ];
        }

        return ["error" => "Idade fora da faixa etária permitida para meninos (8 a 18 anos)."];
    }

    // Cálculos para meninas (faixa etária 8-18)
    private function calcularPercentualGorduraMeninas($idade, $antropometria) {
        if (empty($antropometria['peso'])) {
            return ["error" => "Peso não disponível."];
        }

        if ($idade >= 8 && $idade <= 18) {
            $percentual_gordura = $this->formulas->calcularPercentualGorduraMeninas($antropometria);
            $massa_gorda = $this->formulas->calcularMassaGorda($antropometria, $percentual_gordura);
            $massa_magra = $this->formulas->calcularMassaMagra($antropometria, $massa_gorda);

            return [
                'percentual_gordura_meninas' => number_format($percentual_gordura, 2),
                'massa_gorda' => number_format($massa_gorda, 2),
                'massa_magra' => number_format($massa_magra, 2),
                'peso' => number_format($antropometria['peso'], 2),
            ];
        }

        return ["error" => "Idade fora da faixa etária permitida para meninas (8 a 18 anos)."];
    }
}

// Processamento da requisição
$id_aluno = $_GET['id'] ?? null;
$formula = $_GET['formula'] ?? null;
$data_escolhida = $_GET['data'] ?? null;

$controller = new CalcularController();
echo $controller->processarCalculo($id_aluno, $formula, $data_escolhida);
?>
