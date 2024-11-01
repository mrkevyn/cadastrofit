<?php

class Formulas {
    public function calcularDensidadeCorporalMasculina($antropometria, $idade) {
        $constante = 1.109380;
        $coeficiente1 = -0.0008267;
        $coeficiente2 = 0.0000016;
        $coeficiente3 = -0.0002574;

        $densidade_corporal = $constante +
                              $coeficiente1 * ($antropometria['peitoral'] + $antropometria['abdomem'] + $antropometria['coxa_medial']) +
                              $coeficiente2 * pow(($antropometria['peitoral'] + $antropometria['abdomem'] + $antropometria['coxa_medial']), 2) +
                              $coeficiente3 * $idade;

        return $densidade_corporal;
    }

    public function calcularDensidadeCorporalFeminina($antropometria, $idade) {
        $constante = 1.0994921;
        $coeficiente1 = -0.0009929;
        $coeficiente2 = 0.0000023;
        $coeficiente3 = -0.0001392;

        $densidade_corporal = $constante +
                              $coeficiente1 * ($antropometria['triceps'] + $antropometria['supra_iliaca_medial'] + $antropometria['coxa_medial']) +
                              $coeficiente2 * pow(($antropometria['triceps'] + $antropometria['supra_iliaca_medial'] + $antropometria['coxa_medial']), 2) +
                              $coeficiente3 * $idade;

        return $densidade_corporal;
    }

    public function calcularPercentualGorduraCorporal($densidade_corporal) {
        // Constantes utilizadas na fórmula
        $constante = 4.95;
        $subtrativo = 4.50;
    
        // Converte a densidade corporal para float
        $densidade_corporal = floatval($densidade_corporal);
    
        // Verifica se a densidade corporal é maior que zero para evitar divisão por zero
        if ($densidade_corporal > 0) {
            // Aplica a fórmula de Siri para calcular o percentual de gordura corporal
            $percentual_gordura_corporal = (($constante / $densidade_corporal) - $subtrativo) * 100;
    
            // Garante que o resultado não seja negativo
            return max($percentual_gordura_corporal, 0);
        }
    
        // Retorna um valor padrão se a densidade corporal for zero ou inválida
        return 0; // ou outro valor padrão que você preferir
    }

    public function calcularPercentualGorduraMeninos($antropometria) {
        $percentual_gordura = (0.735 * ($antropometria['triceps'] + $antropometria['perna']) + 1);
        return $percentual_gordura;
    }

    public function calcularPercentualGorduraMeninas($antropometria) {
        $percentual_gordura = (0.610 * ($antropometria['triceps'] + $antropometria['perna']) + 5.1);
        return $percentual_gordura;
    }

    public function calcularMassaGorda($antropometria, $percentual_gordura_corporal){
        if (!isset($antropometria['peso']) || empty($antropometria['peso'])) {
            return 'Peso não disponível. Não é possível calcular a massa gorda.';
        }

        $massa_gorda = $antropometria['peso'] * ($percentual_gordura_corporal/100);
        return $massa_gorda;

    }

    public function calcularMassaMagra($antropometria, $massa_gorda){
        $massa_magra = $antropometria['peso'] - $massa_gorda;
        return $massa_magra;
    }
}
?>
