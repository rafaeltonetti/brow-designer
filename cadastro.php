<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST['name'];
    $email = $_POST['email'];
    $telefone = $_POST['phone'];
    $cpf = $_POST['cpf'];
    $senha = $_POST['password'];

    if (empty($nome) || empty($email) || empty($telefone) || empty($cpf) || empty($senha)) {
        echo "<script>alert('Por favor, preencha todos os campos.');</script>";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, telefone, cpf, senha) VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Erro na preparação da query: " . $conn->error);
        }

        $stmt->bind_param("sssss", $nome, $email, $telefone, $cpf, $senha_hash);

        if ($stmt->execute()) {
            echo "<script>alert('Cadastro realizado com sucesso! Redirecionando para o login.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar. Tente novamente. Erro: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }

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