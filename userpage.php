<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];

// Lógica para buscar os dados do usuário no banco de dados
$stmt = $conn->prepare("SELECT nome, email, telefone, cpf FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

// Lógica para processar a alteração de senha
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    $stmt_password = $conn->prepare("SELECT senha FROM usuarios WHERE id = ?");
    $stmt_password->bind_param("i", $id_usuario);
    $stmt_password->execute();
    $result_password = $stmt_password->get_result();
    $user_password_hash = $result_password->fetch_assoc()['senha'];

    if (password_verify($current_password, $user_password_hash)) {
        if ($new_password === $confirm_password) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt_update = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $stmt_update->bind_param("si", $new_password_hash, $id_usuario);
            if ($stmt_update->execute()) {
                echo "<script>alert('Senha alterada com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao alterar a senha.');</script>";
            }
            $stmt_update->close();
        } else {
            echo "<script>alert('A nova senha e a confirmação não coincidem.');</script>";
        }
    } else {
        echo "<script>alert('Senha atual incorreta.');</script>";
    }
    $stmt_password->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="css/userpage.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav id="navbar">
            <div class="navbar">
                <a href="index.php"><div class="logo">BROW CURSOS</div></a>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cursos.php">Cursos</a></li>
                    <li><a href="certificados.php">Certificados</a></li>
                </ul>

                <div class="user-menu">
                    <div class="avatar" id="avatar">
                        <img src="img/perfil.png" alt="Foto de Perfil">
                    </div>
                    <div class="dropdown" id="menu">
                        <a href="userpage.php">Perfil</a>
                        <a href="certificados.php">Certificados</a>
                        <a href="ajuda.php">Ajuda</a>
                        <hr>
                        <a href="logout.php">Sair</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="profile-section">
            <div class="primeirosLinks">
                <h1>Perfil</h1>
                <ul>
                    <li><a href="certificados.php">Certificados</a></li>
                    <li><a href="ajuda.php">Ajuda</a></li>
                </ul>
            </div>
            <div class="profile-container">
                <h1>Meu Perfil</h1>
                
                <div class="profile-info">
                    <img src="img/perfil.png" alt="Foto de Perfil" class="profile-picture">
                    <div class="user-details">
                        <p>Nome</p>
                        <p><?php echo htmlspecialchars($usuario['nome']); ?></p>
                        <p>E-mail</p> 
                        <p><?php echo htmlspecialchars($usuario['email']); ?></p>
                        <p>Telefone</p>
                        <p><?php echo htmlspecialchars($usuario['telefone']); ?></p>
                        <p>CPF</p>
                        <p><?php echo htmlspecialchars($usuario['cpf']); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="change-password-section">
            <div class="change-password-container">
                <h2>Alterar Senha</h2>
                <form action="userpage.php" method="post" class="change-password-form">
                    <input type="hidden" name="change_password" value="1">
                    <label for="current-password">Senha Atual:</label>
                    <input type="password" id="current-password" name="current-password" required>

                    <label for="new-password">Nova Senha:</label>
                    <input type="password" id="new-password" name="new-password" required>

                    <label for="confirm-password">Confirmar Nova Senha:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>

                    <button type="submit">Alterar Senha</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Grow Cursos</p>
    </footer>

    <script src="js/user_menu.js"></script>
</body>
</html>