<?php
include 'conexao.php'; // Inclui o arquivo de conexão

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtém os dados do formulário
    $nome = $_POST['name'];
    $email = $_POST['email'];
    $telefone = $_POST['phone'];
    $cpf = $_POST['cpf'];
    $senha = $_POST['password']; // Adicionei o campo de senha, que estava faltando no seu HTML

    // Valida os dados (você pode adicionar mais validações)
    if (empty($nome) || empty($email) || empty($telefone) || empty($cpf) || empty($senha)) {
        echo "<script>alert('Por favor, preencha todos os campos.');</script>";
    } else {
        // Criptografa a senha para segurança
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Prepara a query SQL
        $sql = "INSERT INTO usuarios (nome, email, telefone, cpf, senha) VALUES (?, ?, ?, ?, ?)";
        
        // Prepara a declaração para evitar SQL Injection
        $stmt = $conn->prepare($sql);
        
        // Verifica se a preparação da query foi bem-sucedida
        if ($stmt === false) {
            die("Erro na preparação da query: " . $conn->error);
        }

        // 'sssss' indica que todos os 5 parâmetros são strings
        $stmt->bind_param("sssss", $nome, $email, $telefone, $cpf, $senha_hash);

        // Executa a query
        if ($stmt->execute()) {
            echo "<script>alert('Cadastro realizado com sucesso! Redirecionando para o login.'); window.location.href='login.php';</script>";
        } else {
            // Exibe uma mensagem de erro se a query falhar (ex: CPF ou e-mail já existem)
            echo "<script>alert('Erro ao cadastrar. Tente novamente. Erro: " . $stmt->error . "');</script>";
        }

        // Fecha a declaração
        $stmt->close();
    }

    // Fecha a conexão com o banco de dados no final do script
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="css/cadastro.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="cadastro-container">
        <div class="logo">
            BROW
        </div>
        
        <form action="cadastro.php" method="POST" class="cadastro-form">
            <div class="input-group">
                <label for="name">Nome Completo</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="input-group">
                <label for="phone">Telefone</label>
                <input type="tel" id="phone" name="phone" placeholder="(XX) XXXXX-XXXX" required>
            </div>

            <div class="input-group">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" placeholder="XXX.XXX.XXX-XX" required>
            </div>

            <div class="input-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-cadastro">Cadastrar</button>
            
            <a href="login.php" class="login-link">Já tem uma conta? Faça login</a>
        </form>
    </div>
</body>
</html>