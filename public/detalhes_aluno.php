<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../Classes/Database.php';
include '../Classes/Aluno.php';
include '../Classes/AlunoRepository.php';

$db = new Database();
$alunoRepo = new AlunoRepository($db);
$alunoClass = new Aluno($db);

$id_aluno = isset($_GET['id']) ? $_GET['id'] : null;
$data_escolhida = isset($_GET['data_escolhida']) ? $_GET['data_escolhida'] : null;

if (!$id_aluno) {
    echo "ID do aluno inválido.";
    exit;
}

$aluno = $alunoClass->getAlunoDetails($id_aluno);
$datasAnamnese = $alunoRepo->getDatasAnamnese($id_aluno);
$datasAntropometria = $alunoRepo->getDatasAntropometria($id_aluno);
$datasTestesFisicos = $alunoRepo->getDatasTestesFisicos($id_aluno);

$anamnese = $alunoRepo->getAnamnese($id_aluno, $data_escolhida);
$antropometria = $alunoRepo->getAntropometria($id_aluno, $data_escolhida);

error_log("Peso para cálculo: " . $antropometria['peso']);
//$testesFisicos = $alunoRepo->getTestesFisicos($id_aluno, $data_escolhida);
$objetivos = $alunoRepo->getObjetivos($id_aluno);

$idade = $alunoClass->getIdade($aluno['data_nascimento']);
$sexo = $alunoClass->getAlunoDetails($id_aluno);

$_SESSION['aluno_id'] = $id_aluno;
$_SESSION['data_escolhida'] = $data_escolhida;
$_SESSION['objetivos'] = $objetivos;

$_SESSION['aluno'] = $aluno;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Aluno</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard_detalhes.css">
    <link rel="stylesheet" href="../css/style_calc.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">

</head>

<body>

    <div class="container">

        <div class="content">
            <h2>Detalhes do Aluno</h2>

            <table class="student-table">
                <tr>
                    <td class="student-info">
                        <strong>Nome:</strong> <?= ucwords($aluno['nome']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="student-info">
                        <strong>Telefone:</strong> <?= $aluno['telefone'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="student-info">
                        <strong>Sexo:</strong> <?= $aluno['sexo'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="student-info">
                        <strong>Data de Nascimento:</strong> <?= date('d/m/Y', strtotime($aluno['data_nascimento'])) ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <!-- Adicione quantos espaços você achar adequado -->
                        <strong>Idade:</strong> <?= $idade ?> anos
                    </td>
                </tr>

                <!-- Adicione mais detalhes conforme necessário -->

                <?php if (!empty($data_escolhida)) : ?>
                   
                    <!-- Adicione mais detalhes conforme necessário -->
                <?php endif; ?>
            </table>

            <div class="navBar">
                <div class="cont">
                    <h4>Exames :</h4>
                    <li class="smooth-hover"><a href="../forms/novaAnamneseForm.php?id=<?= $aluno['id'] ?>"><i class="fa-regular fa-clipboard"></i>Anamnese</a></li>
                    <li class="smooth-hover"><a href="../forms/novaAntropometriaForm.php?id=<?= $aluno['id'] ?>"><i class="fa-regular fa-clipboard"></i>Antropometria</a></li>
                    <li class="smooth-hover"><a href="../forms/novoTesteFisicoForm.php?id=<?= $aluno['id'] ?>"><i class="fa-regular fa-clipboard"></i>Testes Físicos</a></li>
                </div>
                <div class="cont">
                    <h4>Relatorios :</h4>
                    <li class="smooth-hover" id="gerarRelatorioBtnRelatorio" style="z-index: 2; position: relative;">
                    <a href="javascript:void(0);" onclick="toggleListaDatas('listaDatasRelatorio', 'setaIconRelatorio');" style="display: flex; align-items: center;">
                    <i class="fas fa-file-alt"></i> Relatório Parcial
                    <i id="setaIconRelatorio" class="fas fa-chevron-down" style="margin-left: 5px;"></i>
                    </a>
                    <ul id="listaDatasRelatorio" style="display:none; text-align: left; padding: 0; margin: 0; z-index: 1; list-style: none;">
                        <?php
                            foreach ($datasAntropometria as $data) {
                            $data_formatada = date('d/m/Y', strtotime($data));
                            echo "<li style='margin: 5px; margin-right: 20px;'><a href=\"../templates/relatorioDate.php?id=" . $aluno['id'] . "&data_escolhida=" . urlencode($data) . "\">" . $data_formatada . "</a></li>";
                            }
                        ?>
                    </ul>
                </li>
                    <li class="smooth-hover"><a href="../templates/relatorio.php?id=<?= $aluno['id'] ?>"><i class="fas fa-file-alt"></i> Relatório Completo</a>

                </div>
                <div class="cont">
                    <h4>Outros :</h4>
                    <li class="smooth-hover" id="Calculadora" style="z-index: 2; position: relative;">

                        <a href="javascript:void(0);" id="calc" style="display: flex; align-items: center;">
                            <i class="fa-solid fa-calculator"></i> Calculadora
                        </a>
                            <?php
                            //foreach ($datas as $data) {
                            //    $data_formatada = date('d/m/Y', strtotime($data));
                                //echo "<li style='margin: 5px; margin-right: 20px;'><a href=\"../dashboards/calculadora.php?id=" . $aluno['id'] . "&data_escolhida=" . $data . "\">" . $data_formatada . "</a></li>";
                            //}
                            ?>
                    </li>
                    <li class="smooth-hover"><a href="../views/excluirAluno.php?id=<?= $aluno['id']; ?>" onclick="return confirmarExclusao(event, <?= $aluno['id']; ?>)"><i class="fas fa-trash-alt" style="color: red;"></i>Excluir aluno</a></li>
                    <li class="smooth-hover"><a href="../dashboards/dashboard_alunos.php"><i class="fas fa-arrow-left"></i> Voltar</a>
                </div>
            </div>

        </div>

        <div id="calculadora" class="hiden">
        <div class="button-group-custom">
            <button class="custom-btn" id="btnTestesFisicos">Testes Físicos</button>
            <button class="custom-btn" id="btnAntropometria">Antropometria</button>
        </div>

    <div class="data_form hiden" id="antropometriaFields">
        <select name="Data" id="selectData">
            <option value="">Data</option>
            <?php
            foreach ($datasAntropometria as $data) {
                $data_formatada = date('d/m/Y', strtotime($data));
                echo "<option value=\"$data\">$data_formatada</option>";
            }
            ?>
        </select>

        <select name="formula" id="selectFormula">
            <option value="">Formulas</option>
            <?php if (strtolower($aluno['sexo']) === "masculino") : ?>
                <option value="percentual_gordura_masculina">Percentual de Gordura Corporal Masculina</option>
                <option value="percentual_gordura_meninos">Percentual de Gordura Corporal Meninos (8-18 anos)</option>
            <?php elseif (strtolower($aluno['sexo']) === "feminino") : ?>
                <option value="percentual_gordura_feminina">Percentual de Gordura Corporal Feminina</option>
                <option value="percentual_gordura_meninas">Percentual de Gordura Corporal Meninas (8-18 anos)</option>
            <?php else : ?>
                <option value="percentual_gordura_masculina">Percentual de Gordura Corporal Masculina</option>
                <option value="percentual_gordura_feminina">Percentual de Gordura Corporal Feminina</option>
                <option value="percentual_gordura_meninos">Percentual de Gordura Corporal Meninos (8-18 anos)</option>
                <option value="percentual_gordura_meninas">Percentual de Gordura Corporal Meninas (8-18 anos)</option>
            <?php endif; ?>
        </select>
    </div>

    <div class="data_form hiden" id="testesFisicosFields">
        <select name="Data" id="selectDataTestesFisicos">
            <option value="">Data</option>
            <?php
            foreach ($datasTestesFisicos as $data) {
                $data_formatada = date('d/m/Y', strtotime($data));
                echo "<option value=\"$data\">$data_formatada</option>";
            }
            ?>
            </select>
        <!-- Exibe VO2 diretamente quando Testes Físicos é selecionado -->
        <p id="vo2Maximo"></p>
    </div>

    <?php
    // Adiciona o campo oculto no HTML
    ?>
    <h4 id="resultado">Resultados :</h4>

    <div class="btn">
        <button id="salvarDadosBtn">Salvar</button>
    </div>
</div>

<script>
// Variáveis para armazenar a última fórmula e data selecionadas
let lastSelectedFormula = '';
let lastSelectedData = '';
let lastSelectedDataTestesFisicos = '';

const idadeAluno = <?= json_encode($idade); ?>;

function verificarRestricaoIdade(formula) {
    const formulasGerais = ["percentual_gordura_masculina", "percentual_gordura_feminina"];
    const formulasEspecificas = ["percentual_gordura_meninos", "percentual_gordura_meninas"];

    if (idadeAluno >= 8 && idadeAluno <= 18 && formulasGerais.includes(formula)) {
        alert("A fórmula selecionada é restrita para alunos com +18 anos. Use as fórmulas específicas para essa faixa etária.");
        return false;
    } else if (idadeAluno < 8 || (idadeAluno > 18 && formulasEspecificas.includes(formula))) {
        alert("Esta fórmula é específica para alunos entre 8 e 18 anos.");
        return false;
    }
    return true;
}

// Atualizar visibilidade dos campos
document.getElementById('btnTestesFisicos').addEventListener('click', function () {
    document.getElementById('antropometriaFields').classList.add('hiden');
    document.getElementById('testesFisicosFields').classList.remove('hiden');
    updateVo2();
    // Esconde o botão salvar ao selecionar Testes Físicos
    document.getElementById('salvarDadosBtn').classList.add('hiden');

});

document.getElementById('btnAntropometria').addEventListener('click', function () {
    document.getElementById('antropometriaFields').classList.remove('hiden');
    document.getElementById('testesFisicosFields').classList.add('hiden');
    updateAntropometriaResults();

    // Mostra o botão salvar ao selecionar Antropometria
    document.getElementById('salvarDadosBtn').classList.remove('hiden');
});

document.getElementById('selectFormula').addEventListener('change', function () {
    const selectedFormula = this.value;
    if (selectedFormula && !verificarRestricaoIdade(selectedFormula)) {
        this.value = '';
    } else if (lastSelectedFormula !== selectedFormula) {
        lastSelectedFormula = selectedFormula;
        updateAntropometriaResults();
    }
});

document.getElementById('selectData').addEventListener('change', function () {
    if (lastSelectedData !== this.value) {
        lastSelectedData = this.value;
        updateAntropometriaResults();
    }
});

document.getElementById('selectDataTestesFisicos').addEventListener('change', function () {
    if (lastSelectedDataTestesFisicos !== this.value) {
        lastSelectedDataTestesFisicos = this.value;
        updateVo2();
    }
});

function updateAntropometriaResults() {
    const selectedFormula = lastSelectedFormula;
    const selectedData = lastSelectedData;
    const idAluno = <?= json_encode($id_aluno); ?>;
    const resultadoContainer = document.getElementById('resultado');

    // Verificar se ambos foram selecionados
    if (selectedFormula && selectedData) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `calcular.php?id=${encodeURIComponent(idAluno)}&formula=${encodeURIComponent(selectedFormula)}&data=${encodeURIComponent(selectedData)}`, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const resposta = JSON.parse(xhr.responseText);
                    resultadoContainer.innerHTML = "";

                    if (resposta.error) {
                        alert("Erro: " + resposta.error);
                    } else {
                        const gorduraPercentual = resposta[selectedFormula];
                        resultadoContainer.innerHTML = ` 
                            <table>
                                <tr><th>Fórmula</th><th>Resultado</th></tr>
                                <tr><td class='linha'>Percentual de Gordura Corporal</td><td class='linha'>${gorduraPercentual}%</td></tr>
                                <tr><td class='linha'>Massa gorda</td><td class='linha'>${resposta.massa_gorda} kg</td></tr>
                                <tr><td class='linha'>Massa magra</td><td class='linha'>${resposta.massa_magra} kg</td></tr>
                                <tr><td class='linha'>Peso</td><td class='linha'>${resposta.peso} kg</td></tr>
                            </table>
                        `;
                    }
                } catch (e) {
                    console.error("Erro ao processar resposta JSON:", e);
                }
            }
        };

        xhr.send();
    }
}

function updateVo2() {
    const idAluno = <?= json_encode($id_aluno); ?>;
    const selectedDataTestesFisicos = lastSelectedDataTestesFisicos;
    const resultadoContainer = document.getElementById('resultado');

    if (!idAluno || !selectedDataTestesFisicos) {
        resultadoContainer.innerHTML = "Selecione uma data válida para os Testes Físicos.";
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('GET', `calcular.php?id=${encodeURIComponent(idAluno)}&formula=VO2_maximo&data=${encodeURIComponent(selectedDataTestesFisicos)}`, true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            try {
                console.log("Resposta completa:", xhr.responseText); // Log da resposta bruta

                const resposta = JSON.parse(xhr.responseText);
                console.log("Resposta JSON:", resposta); // Log da resposta JSON após o parse

                if (resposta.VO2_maximo) {
                    resultadoContainer.innerHTML = `
                        <table>
                            <tr><th>Fórmula</th><th>Resultado</th></tr>
                            <tr><td class='linha'>VO2 Máximo</td><td class='linha'>${resposta.VO2_maximo} ml/kg/min</td></tr>
                        </table>
                    `;
                } else {
                    resultadoContainer.innerHTML = resposta.error || "Erro ao processar o VO2 Máximo.";
                    console.log("Erro específico na resposta:", resposta.error || "Erro desconhecido");
                }
            } catch (e) {
                resultadoContainer.innerHTML = "Erro ao processar a resposta.";
                console.error("Erro ao fazer o parse do JSON:", e); // Log do erro de parse
            }
        } else {
            resultadoContainer.innerHTML = "Erro ao buscar o VO2 Máximo.";
            console.log("Erro no status da resposta:", xhr.status);
        }
    };

    xhr.onerror = function () {
        resultadoContainer.innerHTML = "Erro ao fazer a requisição.";
        console.error("Erro na requisição XMLHttpRequest");
    };

    xhr.send();
}

</script>

<script>
document.getElementById('salvarDadosBtn').addEventListener('click', function() {
    const selectedFormula = document.getElementById('selectFormula').value;
    const selectedData = document.getElementById('selectData').value;
    const idAluno = <?php echo json_encode($_SESSION['aluno_id']); ?>;
    const idadeAluno = <?php echo json_encode($idade); ?>;
    const sexoAluno = <?php echo json_encode($sexo); ?>;

    // Pega os valores direto da tabela (a tabela já está gerada com o valor certo para o sexo)
    const percentualGordura = document.querySelector('#resultado table tr:nth-child(2) td:nth-child(2)').innerText.replace('%', '').trim();
    const massaGorda = document.querySelector('#resultado table tr:nth-child(3) td:nth-child(2)').innerText.replace('kg', '').trim();
    const massaMagra = document.querySelector('#resultado table tr:nth-child(4) td:nth-child(2)').innerText.replace('kg', '').trim();
    const peso = document.querySelector('#resultado table tr:nth-child(5) td:nth-child(2)').innerText.replace('kg', '').trim();

    console.log('Dados para enviar:', { 
        selectedFormula, selectedData, idAluno, idadeAluno, 
        sexoAluno, percentualGordura, massaGorda, massaMagra, peso
    });

    if (selectedFormula && selectedData && idAluno && idadeAluno) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'processarCalculo.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert(response.success);
                    } else {
                        alert('Erro: ' + response.error);
                    }
                } catch (e) {
                    console.error("Erro ao analisar a resposta:", e);
                    alert('Erro ao processar a resposta do servidor: ' + xhr.responseText);
                }
            } else {
                alert('Erro ao salvar os dados. Status: ' + xhr.status);
            }
        };

        // Monta os dados para envio
        const postData = `id_aluno=${encodeURIComponent(idAluno)}&data_escolhida=${encodeURIComponent(selectedData)}&formula_escolhida=${encodeURIComponent(selectedFormula)}&idade_aluno=${encodeURIComponent(idadeAluno)}&percentual_gordura=${encodeURIComponent(percentualGordura)}&massa_gorda=${encodeURIComponent(massaGorda)}&massa_magra=${encodeURIComponent(massaMagra)}&peso=${encodeURIComponent(peso)}`;

        console.log('postData:', postData);
        xhr.send(postData);
    } else {
        alert("Por favor, selecione uma data, uma fórmula, e certifique-se de que os dados do aluno estão corretos.");
    }
});

</script>

    <script src="../script/confirmarExclusao.js"></script>

    <!-- Script para escolher data do relatório -->
    <script src="../script/toggleListaDatas.js"></script>
    <!-- modal calc -->
    <script src="../script/calculadora.js"></script>

</body>
</html>
