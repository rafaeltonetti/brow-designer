<?php
session_start();
include 'conexao.php';

// Verifica se o usuário é um admin logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

$curso_id = null;
$curso_nome = "Curso"; // Valor padrão

// Se o ID do curso foi passado via URL
if (isset($_GET['course_id'])) {
    $curso_id = $_GET['course_id'];
}

// Verifica se o ID do curso foi definido
if ($curso_id) {
    // Busca o nome do curso no banco de dados
    $stmt = $conn->prepare("SELECT nome FROM cursos WHERE id = ?");
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $curso = $result->fetch_assoc();
        $curso_nome = $curso['nome'];
    } else {
        echo "<script>alert('Curso não encontrado.'); window.location.href='GerenciarCursos.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID do curso não especificado.'); window.location.href='admin.php';</script>";
    exit();
}

// Lógica para adicionar uma nova aula
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_aula'])) {
    $titulo_aula = $_POST['titulo_aula'];
    $descricao_aula = $_POST['descricao_aula'];
    $video_url = $_POST['video_url'];

    $stmt_aula = $conn->prepare("INSERT INTO aulas (titulo, descricao, video_url, curso_id) VALUES (?, ?, ?, ?)");
    $stmt_aula->bind_param("sssi", $titulo_aula, $descricao_aula, $video_url, $curso_id);

    if ($stmt_aula->execute()) {
        echo "<script>alert('Aula adicionada com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao adicionar aula: " . $stmt_aula->error . "');</script>";
    }

    $stmt_aula->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Aulas - <?php echo htmlspecialchars($curso_nome); ?></title>
    <link rel="stylesheet" href="css/aulas.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="logo">
            BROW CURSOS
        </div>
        
        <h1 class="page-title">Adicionar Aulas</h1>
        <h2 class="course-subtitle">Curso: <?php echo htmlspecialchars($curso_nome); ?></h2>
        
        <form action="aulas.php?course_id=<?php echo $curso_id; ?>" method="POST" class="add-aula-form">
            <input type="hidden" name="add_aula" value="1">
            <div class="input-group">
                <label for="titulo_aula">Título da Aula</label>
                <input type="text" id="titulo_aula" name="titulo_aula" required>
            </div>
            
            <div class="input-group">
                <label for="descricao_aula">Descrição da Aula (Opcional)</label>
                <textarea id="descricao_aula" name="descricao_aula" rows="3"></textarea>
            </div>

            <div class="input-group">
                <label for="video_url">URL do Vídeo</label>
                <input type="text" id="video_url" name="video_url" required>
            </div>
            
            <button type="submit" class="btn-add-aula">Adicionar Aula</button>
            
            <a href="GerenciarCursos.php" class="back-link">Voltar para Gerenciar Cursos</a>
        </form>

    </div>
</body>
</html>