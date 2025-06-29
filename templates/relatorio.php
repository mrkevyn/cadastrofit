<?php
include '../public/gerar_relatorio.php';

$id_aluno = $_SESSION['aluno_id'] ?? null;

if (!$id_aluno) {
    header("Location: dashboard.php");
    exit;
}
function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}

$aluno = $_SESSION['aluno'] ?? null;
$anamneses = $_SESSION['anamneses'] ?? [];
$antropometrias = $_SESSION['antropometrias'] ?? [];
$testesFisicos = $_SESSION['testesFisicos'] ?? [];
$calculos = $_SESSION['calculos'] ?? [];

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório do Aluno</title>
    <link rel="stylesheet" type="text/css" href="../css/relatorio.css">
    <!-- Inclua o jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Inclua a biblioteca html2pdf -->
    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <!-- Biblioteca de gráficos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="../js/relatorio.js"></script>
</head>
<body>
<nav>
    <!-- Botão para baixar em PDF -->
    <button type="button" id="downloadPdf">Baixar em PDF</button>
    <button onclick="window.location.href='../public/detalhes_aluno.php?id=<?= $id_aluno ?>'">Voltar</button>
</nav>

<div class="pdf-container">
    <!-- Dados do Aluno -->
    <section class="section">
        <h1>Relatório do Aluno</h1>
        <section class="subsection">
            <h2>Dados Pessoais</h2>
            <table>
                <tr>
                    <td class="with-margin"><strong>Nome:</strong> <?= ucwords($aluno['nome']) ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <strong>Sexo:</strong> <?= ucwords($aluno['sexo']) ?>
                    </td>
                </tr>
                <tr>
                    <td class="with-margin"><strong>Telefone:</strong><?= $aluno['telefone'] ?></td>
                </tr>
                <tr>
                    <td class="with-margin"> <strong>Avaliador:</strong> <?php echo $_SESSION['nome_admin']; ?></td>
                </tr>
                <tr><td></td> <strong> <?php echo $aluno['id']; ?></strong></tr>
            </table>
        </section>

        <!-- Detalhes da Anamnese -->
        <section class="subsection">
            <h2>Anamnese</h2>
            <table>
                <tr>
                    <?php foreach ($anamneses as $anamnese) : ?>
                        <td>
                            <table>
                                <tr>
                                    <td class="with-margin"><strong>Data:</strong><?=formatarData($anamnese['created_at']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Objetivos:</strong> <?=ucwords($_SESSION['objetivos']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Diabetes:</strong> <?= $anamnese['diabetes'] ?? 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Cardiopatia:</strong> <?= $anamnese['cardiopatia'] ?? 'N/A' ?></td>
                                </tr>
                            </table>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </table>
        </section>

        <!-- Detalhes da Antropometria -->
        <h2>Antropometria</h2>
        <?php if (isset($_SESSION['antropometrias']) && !empty($_SESSION['antropometrias'])): ?>
        <ul>
            <?php foreach ($_SESSION['antropometrias'] as $antropometria): ?>
                <li>
                    <strong>Data:</strong> <?php echo formatarData($antropometria['created_at']); ?>
                    <p><strong>Peso:</strong> <?php echo htmlspecialchars($antropometria['peso']); ?></p>
                    <p><strong>Triceps:</strong> <?php echo htmlspecialchars($antropometria['triceps']); ?></p>
                    <p><strong>ID:</strong><?php echo htmlspecialchars($antropometria['aluno_id']);?></p>
                    <p><strong>ID antropometria:</strong> <?php echo htmlspecialchars ($antropometria['id']); ?> </p>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
            <p>Dados de antropometria não disponíveis.</p>
        <?php endif; ?>
        
        <div class="graph-container">
            <canvas classe="imc" id="grafico-imc" width="300" height="300" style="max-width: 300px; max-height: 300px;"></canvas>
            <canvas id="antropometriaPieChart" width="300" height="300" style="max-width: 300px; max-height: 300px;"></canvas>
        </div>
        
        <!-- Detalhes dos Testes Físicos -->
        <h2>Testes Físicos</h2>
        <?php if (isset($_SESSION['testesFisicos']) && !empty($_SESSION['testesFisicos'])): ?>
        <ul>
            <?php foreach ($_SESSION['testesFisicos'] as $testeFisico): ?>
                <li>
                    <strong>Data:</strong> <?php echo formatarData($testeFisico['created_at']); ?>
                    <p><strong>Banco de Wells:</strong> <?php echo htmlspecialchars($testeFisico['banco_de_wells']); ?></p>
                    <p><strong>Vo2 Máximo:</strong> <?php echo htmlspecialchars($testeFisico['vo2_maximo']);?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Dados dos testes físicos não disponíveis.</p>
    <?php endif; ?>

        <!-- Detalhes dos Cálculos -->
        <h2>Cálculos</h2>
        <?php if (isset($_SESSION['calculos']) && !empty($_SESSION['calculos'])): ?>
        <ul>
            <?php foreach ($_SESSION['calculos'] as $calculo): ?>
                <li>
                    <strong>Data:</strong> <?php echo formatarData($calculo['created_at']); ?>
                    <p><strong>Resultados:</strong> <?php echo htmlspecialchars($calculo['resultado_porcentagem']); ?></p>
                    <p><strong>Massa Magra:</strong><?php echo htmlspecialchars($calculo['massa_magra']);?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Dados dos cálculos não disponíveis.</p>
    <?php endif; ?>

</div>
</body>
</html>
