<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../Classes/Database.php';
include '../Classes/User.php';

$database = new Database();
$user = new User($database);

// Verifica se o usuário está autenticado
if (!$user->isLogged()) {
    header('Location: ../index.php');
    exit;
}

// Verifica se o usuário é admin
if (!$user->isAdmin()) {
    header('Location: ../index.php');
    exit;
}

$usuarios = $user->getAllUsers();

//echo $_SESSION['nome_admin'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Usuários</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="user_config">
            <div class="img_ola">
                <?php if ($user->isAdmin()) : ?>
                    <img src="../img/Group 7.png" alt="">
                <?php endif; ?>
                <h2>Olá, <?php echo $_SESSION['nome_admin']; ?> </h2>
            </div>

            <div class="exit_config">
                <a href="dashboard_alunos.php"><ion-icon name="chevron-back-outline"></ion-icon></a>
            </div>
        </div>

        <div class="content">
            <div class="alunosAtivosebutao">
                <h3>Usuários Ativos</h3>
                <a href="../forms/cadastroUser.php"><button>+ Usuários</button></a>
            </div>

            <div class="search-container">
                <input type="text" id="search" placeholder="Pesquisar por nome...">
            </div>

            <div class="tabela">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr onclick="window.location='../public/user_details.php?id=<?php echo $usuario['id']; ?>'">
                                <td><?= ucwords($usuario['nome']) ?></td>
                                <td><?= $usuario['email'] ?></td>
                                <td><?= $usuario['isadmin'] ? 'Sim' : 'Não'; ?></td>
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
