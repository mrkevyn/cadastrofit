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

if (!$data_escolhida) {
    echo "Data escolhida não foi fornecida.";
    exit;
}

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}

function calcularIdade($dataNascimento) {
    $dataNascimento = new DateTime($dataNascimento);
    $agora = new DateTime();
    $idade = $agora->diff($dataNascimento);
    return $idade->y;
}

// Recupera os dados do aluno
$aluno = $alunoClass->getAlunoDetails($id_aluno);
$anamnese = $alunoRepo->getAnamnese($id_aluno, $data_escolhida);
$antropometria = $alunoRepo->getAntropometria($id_aluno, $data_escolhida);
$testesFisicos = $alunoRepo->getTestesFisicos($id_aluno, $data_escolhida);
$calculos = $alunoRepo->getCalculos($id_aluno, $data_escolhida);
$objetivos = $alunoRepo->getObjetivos($id_aluno);

$idade = $alunoClass->getIdade($aluno['data_nascimento']);

// Armazena os dados na sessão
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
    <title>Relatório do Aluno</title>
    <link rel="stylesheet" type="text/css" href="../css/relatorio.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <script>
        $(document).ready(function () {
            $("#downloadPdf").click(function () {
                var clonedBody = document.body.cloneNode(true);
                $(clonedBody).find("nav").remove();
                $(clonedBody).find("body").css({
                    "margin": "20px", 
                    "padding": "20px", 
                    "box-sizing": "border-box"
                });
                html2pdf().from(clonedBody).set({
                    filename: 'Relatório_' + <?php echo json_encode($anamneseCreatedAtFormatted); ?> + '.pdf'
                }).save();
            });
        });
    </script>
</head>
<body>

<nav>
    <button type="button" id="downloadPdf">Baixar em PDF</button>
    <button onclick="window.location.href='../public/detalhes_aluno.php?id=<?= $id_aluno ?>'">Voltar</button>
</nav>

<div class="pdf-container">
    <section class="section">
        <h1>Relatório do Aluno</h1>
        <section class="subsection">
            <h2>Dados Pessoais</h2>
            <table>
                <tr>
                    <td class="with-margin"><strong>Nome:</strong> <?= ucwords($aluno['nome']) ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <strong>Data da avaliação:</strong> <?= formatarData($antropometria['created_at']) ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <strong>Hora da avaliação:</strong> <?= date('H:i', strtotime($antropometria['created_at'])) ?>
                    </td>
                </tr>
                <tr>
                    <td class="with-margin"><strong>Telefone:</strong> <?= $aluno['telefone'] ?></td>
                </tr>
                <tr>
                    <td class="with-margin">
                        <strong>Data de Nascimento:</strong> <?= date('d/m/Y', strtotime($aluno['data_nascimento'])) ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <strong class="age-label">Idade:</strong> <?= calcularIdade($aluno['data_nascimento']) ?> anos
                    </td>
                </tr>
                <tr>
                    <td>ID: <strong><?=$aluno['id'] ?></strong></td>
                </tr>
            </table>
        </section>

        <section class="subsection">
            <h2>Anamnese</h2>
            <table>
                <tr>
                    <td class="with-margin"><strong>Objetivos:</strong> <?= ucwords($_SESSION['objetivos']) ?></td>
                </tr>
                <tr>
                    <td class="with-margin"><strong>Diabetes:</strong> <?= $anamnese['diabetes'] ?? 'N/A' ?></td>
                </tr>
                <tr>
                    <td class="with-margin"><strong>Cardiopatia:</strong> <?= $anamnese['cardiopatia'] ?? 'N/A' ?></td>
                </tr>
            </table>
        </section>

        <section class="subsection">
            <h2>Antropometria</h2>
            <table>
                <tr>
                    <td class="with-margin"><strong>Braço Relaxado Direito:</strong> <?= $antropometria['braco_relaxado_direito'] ?? 'N/A' ?></td>
                </tr>
                <tr>
                    <td class="with-margin"><strong>Braço Relaxado Esquerdo:</strong> <?= $antropometria['braco_relaxado_esquerdo'] ?? 'N/A' ?></td>
                </tr>
                <tr><td><strong>Peso:</strong> <?= $antropometria['peso'] ?? 'N/A' ?></td></tr>
            </table>
        </section>

        <section class="subsection">
            <h2>Testes Físicos</h2>
            <table>
                <tr>
                    <td class="with-margin"><strong>Banco de Wells:</strong> <?= $testesFisicos['banco_de_wells'] ?? 'N/A' ?></td>
                </tr>
            </table>
        </section>

        <section class="subsection">
            <h2>Cálculos</h2>
            <table>
            <?php if (!empty($calculos)) : ?>
                    <tr>
                        <td class="student-info">
                            <strong>Cálculos:</strong>
                            <ul>
                                <?php foreach ($calculos as $calculo) : ?>
                                    <li><?= $calculo['formula'] ?>: <?= $calculo['resultado_porcentagem'] ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>
        </section>
    </section>
</div>
</body>
</html>
