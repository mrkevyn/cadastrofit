<?php
session_start();
include '../Classes/Database.php';
include '../Classes/TesteFisico.php';
include '../Classes/Aluno.php';

$database = new Database();
$db = $database->getConnection();

$aluno_id = isset($_GET['id']) ? $_GET['id'] : null;

// Verifique se o ID do aluno é válido
if (!$aluno_id) {
    echo "ID do aluno inválido.";
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Cria instâncias das classes
$alunoObj = new Aluno($database);
$testeFisicoObj = new TesteFisico($db);

// Obtém os detalhes do aluno pelo ID
$aluno = $alunoObj->getAlunoDetails($aluno_id);
if (!$aluno) {
    echo "Aluno não encontrado.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/testeFisico.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>Cadastro de Testes Físicos</title>
</head>

<body>
    <!-- <nav>
        <h1>Testes físicos de <?= ucwords($aluno['nome']) ?></h1>
    </nav> -->
    <div class="container">
        <div class="content">
            <form method="post" action="../public/novoTesteFisico.php?id=<?= $aluno['id'] ?>">
                <h1>Testes Físicos de <?= ucwords($aluno['nome']) ?></h1>
                <table>
                    <div class="col-12 mb-1">

                        <h4> Avaliação da Flexibilidade - Sentar e Alcançar:</h4>

                        <tr>
                            <td><input name="novo_banco_de_wells" type="number" min="0" max="1000" step=".01" placeholder="Banco de Wells(cm)" required></td>
                        </tr>
                </table>

                <h4>Teste de caminhada de 12 minutos</h4>

                <table>
                    <tr>
                        <td><input name="nova_distancia_percorrida" type="number" min="0" max="10000" step=".01" placeholder="Distância percorrida(m)" required></td>
                    </tr>

                    <tr>
                        <td><input name="nova_fc_max" type="number" min="0" max="1000" step=".01" placeholder="Frequênica cardíaca máxima" required></td>
                    </tr>

                </table>

                <div class="btns">
                    <button id="btn">Enviar</button>
                </div>

            </form>
        </div>

    </div>

</body>

</html>