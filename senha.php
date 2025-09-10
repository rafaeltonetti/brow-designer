<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci a Senha </title>
    <link rel="stylesheet" href="css/senha.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="forgot-password-container">
        <div class="logo">
            BROW
        </div>

        <p class="instruction-text">
            Por favor, insira o e-mail da sua conta para receber um link de recuperação de senha.
        </p>
        
        <form class="forgot-password-form">
            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <button type="submit" class="btn-submit">Enviar Link de Recuperação</button>
            
            <a href="index.html" class="login-link">Voltar para o Login</a>
        </form>
    </div>
</body>
</html>