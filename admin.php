<?php
session_start();

// Verifica se a sessão do usuário está ativa
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    // Se a sessão não existir, redireciona para a página de login
    header("Location: login.php");
    exit();
}

// Verifica se o usuário é um administrador
if ($_SESSION['is_admin'] != 1) {
    // Se não for admin, redireciona para a home (ou uma página de acesso negado)
    header("Location: index.php"); 
    exit();
}

// Se o código chegou até aqui, o usuário está logado e é um admin
// O restante do conteúdo da página pode ser exibido
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link rel="stylesheet" href="css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <div class="logo">
            BROW CURSOS
        </div>
        
        <h1 class="welcome-text">Bem-vindo, Administrador</h1>
        
        <p class="instruction-text">
            Selecione uma opção para gerenciar o conteúdo do site.
        </p>

        <div class="admin-buttons">
            <a href="AddCurso.php" class="btn-admin">
                Adicionar Novo Curso
            </a>
            
            <a href="GerenciarCursos.php" class="btn-admin">
                Gerenciar Cursos
            </a>

            <a href="GerenciarAlunos.php" class="btn-admin">
                Gerenciar Alunos
            </a>

            <a href="index.php" class="btn-admin">
                Home
            </a>

            <a href="logout.php" class="btn-admin">
                Sair
            </a>
        </div>

    </div>
</body>
</html>