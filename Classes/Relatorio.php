<?php
class Relatorio {
    private $aluno;
    private $anamnese;
    private $antropometria;
    private $testeFisico;
    private $calculo;

    public function __construct($aluno, $anamnese, $antropometria, $testeFisico, $calculo) {
        $this->aluno = $aluno;
        $this->anamnese = $anamnese;
        $this->antropometria = $antropometria;
        $this->testeFisico = $testeFisico;
        $this->calculo = $calculo;
    }

    public function formatarData($data) {
        return date('d/m/Y', strtotime($data));
    }

    public function gerar() {
        // Pegar detalhes do aluno
        $aluno = $this->aluno->read();

        // Pegar anamneses
        $anamneses = $this->anamnese->read();

        // Pegar antropometrias
        $antropometrias = $this->antropometria->read();

        // Pegar testes físicos
        $testesFisicos = $this->testeFisico->read();

        // Pegar cálculos
        $calculos = $this->calculo->read();

        // Código HTML para o relatório...
        // Este será similar ao código original mas usando os métodos da classe
        // para obter dados e formatação.
    }
}
