<?php
class User {
    private $db;
    private $userId;
    private $isadmin;
    private $message;

    public function __construct(Database $db) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->db = $db->getConnection();

        if (isset($_SESSION['usuario_id'])) {
            $this->userId = $_SESSION['usuario_id'];
            $this->isadmin = isset($_SESSION['isadmin']) ? $_SESSION['isadmin'] : false;
        }
    }

    public function isLogged() {
        return isset($_SESSION['usuario_id']);
    }

    public function getUserData() {
        if ($this->isLogged()) {
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :usuario_id");
            $stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
            $stmt->execute();
            return $stmt->fetch();
        }
        return null;
    }

    public function isAdmin() {
        return $this->isadmin;
    }

    public function login($email, $senha) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $usuario = $stmt->fetch();

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['isadmin'] = $usuario['isadmin'];
                $_SESSION['nome_admin'] = $usuario['nome'];

                header('Location: dashboards/dashboard_alunos.php');
                exit;
            } else {
                $_SESSION['login_error'] = "E-mail ou senha inválidos.";
                header('Location: index.php');
                exit;
            }
        } catch (Exception $e) {
            throw new Exception("Erro na execução da consulta: " . $e->getMessage());
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM usuarios");
        return $stmt->fetchAll();
    }

    public function changePassword($id, $nova_senha, $confirma_nova_senha) {
        if ($nova_senha === $confirma_nova_senha) {
            $hashed_nova_senha = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE usuarios SET senha = :nova_senha WHERE id = :id");
            $stmt->bindParam(':nova_senha', $hashed_nova_senha);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } else {
            throw new Exception("A nova senha e a confirmação não coincidem.");
        }
    }

    public function getUserDetails($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :usuario_id");
        $stmt->bindParam(':usuario_id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCurrentUserId() {
        return $this->userId;
}

    public function getUserByEmail($email) {
        $query = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
}

    public function setTokenAndExpiration($email, $token, $expiration) {
        $query = $this->db->prepare("UPDATE usuarios SET token = :token, data_expiracao = :expiracao WHERE email = :email");
        $query->bindParam(':token', $token);
        $query->bindParam(':expiracao', $expiration);
        $query->bindParam(':email', $email);
        $query->execute();
    }

    public function validateToken($email, $token) {
        $query = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email AND token = :token AND data_expiracao > NOW()");
        $query->bindParam(':email', $email);
        $query->bindParam(':token', $token);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($email, $nova_senha) {
        $query = $this->db->prepare("UPDATE usuarios SET senha = :senha, token = NULL, data_expiracao = NULL WHERE email = :email");
        $query->bindParam(':senha', password_hash($nova_senha, PASSWORD_DEFAULT));
        $query->bindParam(':email', $email);
        return $query->execute();
    }

    public function registerUser($nome, $email, $senha, $isAdmin) {
        $verificarEmail = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $verificarEmail->bindParam(':email', $email);
        $verificarEmail->execute();

        if ($verificarEmail->rowCount() > 0) {
            $this->message = 'Erro: O e-mail já está em uso. Escolha outro e-mail.';
            return false;
        } else {
            $inserirUsuario = $this->db->prepare("INSERT INTO usuarios (nome, email, senha, isadmin) VALUES (:nome, :email, :senha, :isadmin)");
            $inserirUsuario->bindParam(':nome', $nome);
            $inserirUsuario->bindParam(':email', $email);
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $inserirUsuario->bindParam(':senha', $senhaHash);
            $inserirUsuario->bindParam(':isadmin', $isAdmin, PDO::PARAM_BOOL);

            if ($inserirUsuario->execute()) {
                $this->message = 'Novo usuário cadastrado com sucesso!';
                header("Location: ../dashboards/dashboard_usuarios.php?id={$_SESSION['usuario_id']}");
                exit;
            } else {
                $this->message = 'Erro ao cadastrar novo usuário. Tente novamente.';
                return false;
            }
        }
    }

    public function deleteUser($idUsuario) {
        $conn = $this->db;

        // Consulta o banco de dados para obter os detalhes do usuário
        $stmt = $conn->prepare("SELECT id, nome, isadmin FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $idUsuario);
        $stmt->execute();
        $detalhesUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$detalhesUsuario) {
            echo '<script>';
            echo 'alert("Usuário não encontrado.");';
            echo '</script>';
            exit;
        }

        // Verifica se o usuário é o último administrador
        $queryAdmins = $conn->prepare("SELECT COUNT(*) as total FROM usuarios WHERE isadmin = :isadmin");
        $queryAdmins->bindParam(':isadmin', $detalhesUsuario['isadmin'], PDO::PARAM_BOOL);
        $queryAdmins->execute();
        $totalAdmins = $queryAdmins->fetch(PDO::FETCH_ASSOC)['total'];

        if ($_SESSION['isadmin'] && $detalhesUsuario['isadmin'] && $totalAdmins <= 1) {
            echo '<script>';
            echo 'alert("Você não pode excluir o último administrador.");';
            echo 'window.location.href = "../dashboards/dashboard_usuarios.php?id=' . $idUsuario . '";';
            echo '</script>';
            exit;
        }

        // Exclui o usuário do banco de dados
        $stmtExcluir = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmtExcluir->bindParam(':id', $idUsuario);
        $stmtExcluir->execute();

        // Redireciona para o painel após a exclusão
        echo '<script>';
        echo 'alert("Exclusão feita com sucesso.");';
        echo 'window.location.href = "../dashboards/dashboard_usuarios.php?id=' . $_SESSION['usuario_id'] . '";';
        echo '</script>';

        exit;
    }

    public function getMessage() {
        return $this->message;
    }
}
?>