<?php
session_start();
include 'conexao.php'; // Inclui o arquivo de conexão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['password'];

    // Prepara a consulta para evitar injeção de SQL
    $sql = "SELECT id, nome, senha, is_admin FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Erro na preparação da query: " . $conn->error);
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        
        // Verifica se a senha digitada corresponde ao hash do banco de dados
        if (password_verify($senha, $usuario['senha'])) {
            // Senha correta, inicia a sessão
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nome_usuario'] = $usuario['nome'];
            $_SESSION['is_admin'] = $usuario['is_admin'];

            if ($usuario['is_admin']) {
                // Se for administrador, redireciona para a página de admin
                header("Location: admin.php");
            } else {
                // Se for um usuário normal, redireciona para a página home
                header("Location: index.php");
            }
            exit();
        } else {
            // Senha incorreta
            echo "<script>alert('E-mail ou senha incorretos.');</script>";
        }
    } else {
        // E-mail não encontrado
        echo "<script>alert('E-mail ou senha incorretos.');</script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Grow Cursos</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            BROW DESIGN
        </div>
        
        <form action="login.php" method="POST" class="login-form">
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember">
                <label for="remember">Lembrar de mim</label>
            </div>
            
            <button type="submit" class="btn-login">Entrar</button>
            
            <a href="senha.php" class="forgot-password">Esqueceu sua senha?</a>
        </form>
    </div>
</body>
</html>