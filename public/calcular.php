<?php
require_once '../Classes/AlunoRepository.php';
require_once '../Classes/Aluno.php';
require_once '../Classes/Formulas.php';
require_once '../Classes/Database.php';

header('Content-Type: application/json');

class CalcularController {
    private $alunoRepository;
    private $aluno;
    private $formulas;

    public function __construct() {
        $database = new Database();
        $this->alunoRepository = new AlunoRepository($database);
        $this->aluno = new Aluno($database);
        $this->formulas = new Formulas();
    }

    public function processarCalculo($id_aluno, $formula, $data_escolhida) {
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
        $resultado = $this->calcularFormula($formula, $idade, $antropometria);

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

    private function calcularFormula($formula, $idade, $antropometria) {
        switch ($formula) {
            case 'percentual_gordura_masculina':
                return $this->calcularPercentualGorduraMasculina($idade, $antropometria);
            case 'percentual_gordura_feminina':
                return $this->calcularPercentualGorduraFeminina($idade, $antropometria);
            case 'percentual_gordura_meninos':
                return $this->formulas->calcularPercentualGorduraMeninos($antropometria);
            case 'percentual_gordura_meninas':
                return $this->formulas->calcularPercentualGorduraMeninas($antropometria);
            default:
                return ["error" => "Fórmula desconhecida."];
        }
    }

    private function formatarResposta($resultado, $formula) {
        if (is_array($resultado)) {
            return json_encode($resultado);
        } elseif (isset($resultado["error"])) {
            return json_encode($resultado); // Retorno do erro
        } elseif (is_numeric($resultado)) {
            return json_encode([$formula => number_format($resultado, 2)]);
        } else {
            return $this->responderErro("Erro desconhecido ao processar o cálculo.");
        }
    }

    private function calcularPercentualGorduraMasculina($idade, $antropometria) {
        if ($idade >= 8 && $idade <= 18) {
            $percentual_gordura = $this->formulas->calcularPercentualGorduraMeninos($antropometria);
        } else {
            $densidade_corporal = $this->formulas->calcularDensidadeCorporalMasculina($antropometria, $idade);
            if (!is_numeric($densidade_corporal)) {
                return ["error" => $densidade_corporal];
            }
            $percentual_gordura = $this->formulas->calcularPercentualGorduraCorporal($densidade_corporal);
        }
    
        $massa_gorda = $this->formulas->calcularMassaGorda($antropometria, $percentual_gordura);
        $massa_magra = $this->formulas->calcularMassaMagra($antropometria, $massa_gorda); // Usando a fórmula existente
    
        // Adicionando peso ao retorno
        $peso = $antropometria['peso'] ?? null; // Pegando o peso, caso exista
    
        return [
            'percentual_gordura_masculina' => number_format($percentual_gordura, 2),
            'massa_gorda' => number_format($massa_gorda, 2),
            'massa_magra' => number_format($massa_magra, 2),
            'peso' => number_format($peso, 2) // Adicionando peso ao retorno
        ];
    }
        

    private function calcularPercentualGorduraFeminina($idade, $antropometria) {
        if ($idade >= 8 && $idade <= 18) {
            return $this->formulas->calcularPercentualGorduraMeninas($antropometria);
        } else {
            $densidade_corporal = $this->formulas->calcularDensidadeCorporalFeminina($antropometria, $idade);
            if (!is_numeric($densidade_corporal)) {
                return ["error" => $densidade_corporal];
            }
            return $this->formulas->calcularPercentualGorduraCorporal($densidade_corporal);
        }
    }
}

// Processamento da requisição
$id_aluno = $_GET['id'] ?? null;
$formula = $_GET['formula'] ?? null;
$data_escolhida = $_GET['data'] ?? null;

$controller = new CalcularController();
echo $controller->processarCalculo($id_aluno, $formula, $data_escolhida);
?>
