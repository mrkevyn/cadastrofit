<?php
require_once '../Controllers/EsqueciSenhaController.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+Wy6p9w1YY6L/EpuPbZ+8Fl/VPBk2ZIbp44" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Esqueceu a Senha</title>
    <link rel="stylesheet" type="text/css" href="../css/forgetPassword.css">
</head>

<body>

    <div class="login-container">
            <h1>Redefinição de Senha</h1>
            <p>Informe seu endereço de e-mail para redefinir sua senha.</p>

            <!-- Formulário para inserir o e-mail -->
            <form action="forget_password.php" method="post">
                <div class="input-group">
                    <input type="email" name="email" placeholder="E-mail" required>
                </div>

                <button type="submit" class="login-btn" name="submit">Redefinir de Senha</button>
            </form>

           <?php if (isset($erro)) { echo '<div class="alert alert-danger text-center" role="alert" style="width: 90%; margin: 0 auto;">'. $erro .'</div>'; } ?>
        </div>
    </div>

</body>
</html>
