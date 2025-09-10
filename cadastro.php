<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro </title>
    <link rel="stylesheet" href="css/cadastro.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="cadastro-container">
        <div class="logo">
            BROW
        </div>
        
        <form class="cadastro-form">
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
            
            <button type="submit" class="btn-cadastro">Cadastrar</button>
            
            <a href="login.php" class="login-link">Já tem uma conta? Faça login</a>
        </form>
    </div>
</body>
</html>