<?php
session_start();
include 'conexao.php';

// Verifica se o usuário é um admin logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Lógica para processar o formulário de Adicionar Curso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_curso = $_POST['course-name'];
    $descricao_curso = $_POST['course-description'];
    
    // Processamento da imagem de capa
    $capa_curso = '';
    if (isset($_FILES['course-cover']) && $_FILES['course-cover']['error'] == 0) {
        $upload_dir = 'uploads/capas/';
        
        // Crie o diretório se ele não existir e verifique se foi criado com sucesso
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                // Se a pasta não puder ser criada, exibe um erro
                die("Erro: Não foi possível criar o diretório de uploads. Verifique as permissões da pasta.");
            }
        }
        
        $nome_arquivo = basename($_FILES['course-cover']['name']);
        $caminho_arquivo = $upload_dir . uniqid() . '_' . $nome_arquivo;
        
        // Tente mover o arquivo e verifique por erros
        if (move_uploaded_file($_FILES['course-cover']['tmp_name'], $caminho_arquivo)) {
            $capa_curso = $caminho_arquivo;
        } else {
            // Exibe o erro de upload
            die("Erro ao fazer upload da capa. Código do erro: " . $_FILES['course-cover']['error']);
        }
    }
    
    // Insere os dados do curso no banco de dados
    $stmt = $conn->prepare("INSERT INTO cursos (nome, descricao, capa) VALUES (?, ?, ?)");
    if ($stmt === false) {
        // Exibe um erro se a query não puder ser preparada
        die("Erro na preparação da query: " . $conn->error);
    }
    
    $stmt->bind_param("sss", $nome_curso, $descricao_curso, $capa_curso);
    
    if ($stmt->execute()) {
        // Obtém o ID do curso recém-criado
        $novo_curso_id = $conn->insert_id;
        
        // Redireciona para a página de aulas, passando o ID do curso
        header("Location: aulas.php?course_id=" . $novo_curso_id);
        exit();
    } else {
        // Exibe um erro se a execução da query falhar
        die("Erro ao adicionar o curso: " . $stmt->error);
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
    <title>Adicionar Curso</title>
    <link rel="stylesheet" href="css/add-curso.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <div class="logo">
            BROW DESIGNER
        </div>
        
        <h1 class="page-title">Adicionar Novo Curso</h1>
        
        <form action="AddCurso.php" method="POST" class="add-course-form" enctype="multipart/form-data">
            
            <div class="input-group">
                <label for="course-name">Nome do Curso</label>
                <input type="text" id="course-name" name="course-name" required>
            </div>
            
            <div class="input-group">
                <label for="course-description">Sobre o Curso</label>
                <textarea id="course-description" name="course-description" rows="5" required></textarea>
            </div>

            <div class="input-group file-upload-group">
                <label for="course-cover">Capa do Curso</label>
                <div class="custom-file-upload">
                    <input type="file" id="course-cover" name="course-cover" accept="image/*" required onchange="displayFileName(this)">
                    <span id="file-name">Nenhum arquivo selecionado</span>
                </div>
                <p class="file-instruction">Clique para escolher uma imagem ou arraste e solte aqui.</p>
            </div>

            <script>
                function displayFileName(input) {
                    var fileName = input.files[0] ? input.files[0].name : "Nenhum arquivo selecionado";
                    document.getElementById('file-name').textContent = fileName;
                }
            </script>
            
            <button type="submit" class="btn-next">Próximo</button>
            
            <a href="admin.php" class="back-link">Voltar para o Admin</a>
        </form>
    </div>
</body>
</html>