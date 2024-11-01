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

$anamnese = $alunoRepo->getAnamnese($id_aluno, $data_escolhida);
$antropometria = $alunoRepo->getAntropometria($id_aluno, $data_escolhida);
//$testesFisicos = $alunoRepo->getTestesFisicos($id_aluno, $data_escolhida);
$objetivos = $alunoRepo->getObjetivos($id_aluno);

$idade = $alunoClass->getIdade($aluno['data_nascimento']);

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
            <div class="data_form">
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

            <?php
                // Adiciona o campo oculto no HTML
                echo "<input type='hidden' id='idadeAluno' value='$idade'>";
            ?>
            <h4 id="resultado">Resultados :</h4>

            <div class="btn">
                <button id="salvarDadosBtn">Salvar</button>
            </div>
        </div>

    </div>

    <script>
        document.getElementById('selectFormula').addEventListener('change', function() {
    var selectedFormula = this.value;
    var idadeAluno = parseInt(document.getElementById('idadeAluno').value);
    var idAluno = <?php echo json_encode($id_aluno); ?>;

    // Verifica se a fórmula selecionada é para percentual de gordura e a idade está entre 8 e 18
    if ((selectedFormula === 'percentual_gordura_masculina' || selectedFormula === 'percentual_gordura_feminina') && idadeAluno >= 8 && idadeAluno <= 18) {
            alert("É impossível calcular usando esta fórmula pois o aluno tem entre 8 e 18 anos de idade.");
            this.value = ''; // Reseta o valor do campo de seleção
            return; // Impede a execução do cálculo
        }
    // Lógica para fazer a requisição AJAX
    var selectedData = document.getElementById('selectData').value;

    if (selectedFormula && selectedData) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'calcular.php?id=' + encodeURIComponent(id_aluno) + '&formula=' + encodeURIComponent(selectedFormula) + '&data=' + encodeURIComponent(selectedData), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var resultado = xhr.responseText;
                document.getElementById('resultado').innerText = "Percentual de Gordura Corporal: " + resultado + "%";
            }
        };
        xhr.send();
    } else {
        alert("Por favor, selecione uma data e uma fórmula.");
    }
});

document.getElementById('selectFormula').addEventListener('change', function () {
    const selectedFormula = this.value;
    const selectedData = document.getElementById('selectData').value;
    const idAluno = <?php echo json_encode($id_aluno); ?>;

    if (!idAluno) {
        alert("ID do aluno não está definido corretamente.");
        return;
    }

    if (selectedFormula && selectedData) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `calcular.php?id=${encodeURIComponent(idAluno)}&formula=${encodeURIComponent(selectedFormula)}&data=${encodeURIComponent(selectedData)}`, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const resposta = JSON.parse(xhr.responseText);

                    if (resposta.error) {
                        alert("Erro: " + resposta.error);
                    } else {
                        const resultadoContainer = document.getElementById('resultado');
                        resultadoContainer.innerHTML = "";  // Limpa o conteúdo anterior

                        if (selectedFormula === 'percentual_gordura_masculina' && resposta.percentual_gordura_masculina && resposta.massa_gorda && resposta.massa_magra && resposta.peso) {
                            // Exibe tabela para percentual_gordura_masculina com massa magra
                            resultadoContainer.innerHTML = `
                                <table>
                                    <tr><th>Fórmula</th><th>Resultado</th></tr>
                                    <tr><td>Percentual de Gordura Corporal</td><td>${resposta.percentual_gordura_masculina}%</td></tr>
                                    <tr><td>Massa gorda</td><td>${resposta.massa_gorda} kg</td></tr>
                                    <tr><td>Massa magra</td><td>${resposta.massa_magra} kg</td></td>
                                    <tr><td>Peso</td><td>${resposta.peso} kg</td></tr>
                                </table>
                            `;
                        } else {
                            // Exibe resultado único para outras fórmulas
                            for (let key in resposta) {
                                const unidade = key.includes('percentual_gordura') ? '%' : 'kg';
                                const formulaDisplayName = key.replace(/_/g, ' ').replace(/\b\w/g, match => match.toUpperCase());
                                resultadoContainer.innerHTML += `<p>${formulaDisplayName}: ${resposta[key]} ${unidade}</p>`;
                            }
                        }
                    }
                } catch (e) {
                    alert('Erro ao processar a resposta JSON: ' + e.message);
                }
            } else {
                alert('Erro na requisição AJAX. Status: ' + xhr.status);
            }
        };

        xhr.onerror = function () {
            alert('Erro ao enviar a requisição.');
        };

        xhr.send();
    } else {
        alert("Por favor, selecione uma data e uma fórmula.");
    }
});


</script>

    </script>

<script>
document.getElementById('salvarDadosBtn').addEventListener('click', function() {
    var selectedFormula = document.getElementById('selectFormula').value;
    var selectedData = document.getElementById('selectData').value;
    var idAluno = <?php echo json_encode($_SESSION['aluno_id']); ?>;
    var idadeAluno = <?php echo json_encode($idade); ?>;

    console.log('selectedFormula:', selectedFormula);
    console.log('selectedData:', selectedData);
    console.log('idAluno:', idAluno);
    console.log('idadeAluno:', idadeAluno);

    if (selectedFormula && selectedData && idAluno && idadeAluno) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'processarCalculo.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                console.log(xhr.responseText);
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    alert(response.success);
                } else {
                    alert('Erro: ' + response.error);
                }
            } else {
                alert('Erro ao salvar os dados.');
            }
        };
        var postData = 'id_aluno=' + encodeURIComponent(idAluno) + 
                       '&data_escolhida=' + encodeURIComponent(selectedData) + 
                       '&formula_escolhida=' + encodeURIComponent(selectedFormula) + 
                       '&idade_aluno=' + encodeURIComponent(idadeAluno);
        console.log('postData:', postData); // Log the postData for debugging
        xhr.send(postData);
    } else {
        alert("Por favor, selecione uma data, uma fórmula, e certifique-se de que os dados do aluno estão corretos.");
    }
});
</script>

    <script>
        function confirmarExclusao(event, alunoId) {
            var resposta = confirm("Tem certeza que deseja excluir este aluno?");
            if (!resposta) {
                window.location.replace("../public/detalhes_aluno.php?id=<?= $aluno['id']; ?>");
                return false;
            }
            return true;
        }

        // Impede a propagação do evento de clique da tr para os elementos filhos
        document.querySelectorAll('.delete-container').forEach(function(container) {
            container.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
    </script>

    <!-- Script para escolher data do relatório -->
    <script>
        function toggleListaDatas(listaId, iconeId) {
            var listaDatas = document.getElementById(listaId);
            var setaIcon = document.getElementById(iconeId);

            if (listaDatas.style.display === 'none') {
                listaDatas.style.display = 'block';
                setaIcon.classList.remove('fa-chevron-down');
                setaIcon.classList.add('fa-chevron-up');
            } else {
                listaDatas.style.display = 'none';
                setaIcon.classList.remove('fa-chevron-up');
                setaIcon.classList.add('fa-chevron-down');
            }
        }
    </script>
    <!-- modal calc -->
    <script>
        let calc = document.querySelector("#calc")

        calc.addEventListener("click", () => {
            document.querySelector("#calculadora").classList.toggle("calculadora")
            document.querySelector("#calculadora").classList.toggle("hiden")
        })
    </script>

</body>
</html>