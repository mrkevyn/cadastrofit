<?php
include '../Classes/Database.php';
include '../Classes/User.php';

$db = new Database();
$user = new User($db);

// Verifica se o usuário está autenticado
if (!$user->isLogged()) {
    header('Location: ../index.php');
    exit;
}

// Obtém o ID do usuário cuja senha será alterada
$usuario_id = $_GET['id'];

if (!$usuario_id) {
    header('Location: ../index.php');
    exit;
}

// Consulta o banco de dados para obter informações do usuário
$usuario = $user->getUserDetails($usuario_id);

$error = null;

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $nova_senha = $_POST['nova_senha'];
    $confirma_nova_senha = $_POST['confirma_nova_senha'];

    try {
        $user->changePassword($usuario_id, $nova_senha, $confirma_nova_senha);
        echo "Senha atualizada com sucesso!";
        header('Location: ../index.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocar Senha</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../css/changePassword.css">
</head>
<body>

    <div class="container">

        <div class="login-container">
            <h1>Trocar Senha</h1>
            <p>Você está trocando a senha do usuário: <?php echo ucwords($usuario['nome']); ?></p>

            <form method="post" action="">
                <div class="input-group">
                    <input type="password" name="senha_atual" placeholder="Senha Atual" required>
                </div>

                <div class="input-group">
                    <input type="password" name="nova_senha" placeholder="Nova Senha" required>
                </div>

                <div class="input-group">
                    <input type="password" name="confirma_nova_senha" placeholder="Confirme a Nova Senha" required>
                </div>

                <?php if ($error): ?>
                    <div id="loginErrorMessage" class="alert alert-danger text-center alert-sm" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="login-btn" name="submit">Trocar Senha</button>
            </form>
        </div>
    </div>

</body>
</html>
