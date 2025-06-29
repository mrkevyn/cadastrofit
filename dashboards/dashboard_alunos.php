<?php
require_once '../Classes/Database.php';
require_once '../Classes/User.php';
require_once '../Classes/Dashboard.php';

$db = new Database();
$user = new User($db);
$dashboard = new Dashboard($db);

if (!$user->isLogged()) {
    header('Location: ../index.php');
    exit;
}

$alunos = $dashboard->getAlunos();
$userData = $user->getUserData();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Alunos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>

<body>

    <div class="container">
        <div class="user_config">
            <div class="img_ola">
                <?php if ($user->isAdmin()) : ?>
                    <img src="../img/Group 7.png" alt="">
                <?php endif; ?>
                <h2>Olá, <?php echo $userData['nome']; ?> </h2>
            </div>

            <div class="exit_config">
                <a class="config" href="<?php echo $user->isAdmin() ? 'dashboard_usuarios.php?id=' . $userData['id'] : '..\public\user_details.php?id=' . $userData['id']; ?>"><ion-icon name="person"></ion-icon></a>
                <a href="../logout.php"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>

        <div class="content">
            <div class="alunosAtivosebutao">
                <h3>Alunos Ativos</h3>
                <a href="../forms/cadastroAluno.php"><button>+ Aluno</button></a>
            </div>

            <div class="search-container">
                <input type="text" id="search" placeholder="Pesquisar por nome...">
            </div>

            <div class="tabela">
                <table>
                    <thead>
                        <tr>
                            <th>Nome <span class="sort-indicator"><i class="fas"></i></span></th>
                            <th>Telefone <span class="sort-indicator"><i class="fas"></i></span></th>
                            <th>Sexo <span class="sort-indicator"><i class="fas"></i></span></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($alunos as $aluno) : ?>
                            <tr onclick="window.location='../public/detalhes_aluno.php?id=<?php echo $aluno['id']; ?>'">
                                <td><?= ucwords($aluno['nome']) ?></td>
                                <td><?= $aluno['telefone'] ?></td>
                                <td><?= ucwords($aluno['sexo']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../script/dashboard.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
