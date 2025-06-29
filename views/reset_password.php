<?php
require_once '../Controllers/ResetSenhaController.php';

$controller = new ResetSenhaController();
if (!$controller->validateToken()) {
    echo "Link de redefinição inválido ou expirado. Tente novamente.";
    exit;
}

$erro = $controller->handleRequest();
$email = $controller->getEmail();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" type="text/css" href="../css/forgetPassword.css">
</head>

<body>

    <div class="login-container">
            <h1>Redefinir Senha</h1>
            <p>Você está redefinindo a senha para o e-mail: <?php echo htmlspecialchars($email); ?></p>

            <!-- Formulário para redefinir a senha -->
            <form action="reset_password.php" method="post">
                <div class="input-group">
                    <input type="password" name="nova_senha" placeholder="Nova Senha" required>
                </div>

                <button type="submit" class="login-btn" name="submit">Redefinir Senha</button>
            </form>

            <?php if ($erro): ?>
                <div class="alert alert-danger"><?php echo $erro; ?></div>
            <?php endif; ?>
    </div>

</body>
</html>
