<?php
session_start();
include 'conexao.php';

// Verifica se o usuário é um admin logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

$aula_id = null;
$curso_id = null;

// Verifica se o ID da aula e o ID do curso foram passados na URL
if (isset($_GET['aula_id']) && is_numeric($_GET['aula_id']) && isset($_GET['curso_id']) && is_numeric($_GET['curso_id'])) {
    $aula_id = $_GET['aula_id'];
    $curso_id = $_GET['curso_id'];
} else {
    // Redireciona se os IDs não forem especificados
    header("Location: GerenciarCursos.php");
    exit();
}

// Lógica para buscar os dados da aula
$stmt = $conn->prepare("SELECT id, titulo, descricao, video_url FROM aulas WHERE id = ? AND curso_id = ?");
$stmt->bind_param("ii", $aula_id, $curso_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Aula não encontrada.'); window.location.href='gerenciar-aulas.php?curso_id=" . $curso_id . "';</script>";
    exit();
}

$aula = $result->fetch_assoc();

// Lógica para processar a atualização do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novo_titulo = $_POST['aula-titulo'];
    $nova_descricao = $_POST['aula-descricao'];
    $novo_video_url = $_POST['aula-url-video'];

    // Atualiza os dados no banco de dados
    $stmt_update = $conn->prepare("UPDATE aulas SET titulo = ?, descricao = ?, video_url = ? WHERE id = ? AND curso_id = ?");
    $stmt_update->bind_param("sssii", $novo_titulo, $nova_descricao, $novo_video_url, $aula_id, $curso_id);
    
    if ($stmt_update->execute()) {
        echo "<script>alert('Aula atualizada com sucesso!'); window.location.href='gerenciar-aulas.php?curso_id=" . $curso_id . "';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao atualizar a aula: " . $stmt_update->error . "');</script>";
    }
    $stmt_update->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aula - <?php echo htmlspecialchars($aula['titulo']); ?></title>
    <link rel="stylesheet" href="css/add-curso.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <div class="logo">
            BROW CURSOS
        </div>
        
        <h1 class="page-title">Editar Aula</h1>
        
        <form action="editar-aula.php?aula_id=<?php echo $aula_id; ?>&curso_id=<?php echo $curso_id; ?>" method="POST" class="add-course-form">
            
            <div class="input-group">
                <label for="aula-titulo">Título da Aula</label>
                <input type="text" id="aula-titulo" name="aula-titulo" value="<?php echo htmlspecialchars($aula['titulo']); ?>" required>
            </div>
            
            <div class="input-group">
                <label for="aula-descricao">Descrição da Aula</label>
                <textarea id="aula-descricao" name="aula-descricao" rows="5"><?php echo htmlspecialchars($aula['descricao']); ?></textarea>
            </div>

            <div class="input-group">
                <label for="aula-url-video">URL do Vídeo</label>
                <input type="url" id="aula-url-video" name="aula-url-video" value="<?php echo htmlspecialchars($aula['video_url']); ?>" required>
            </div>
            
            <button type="submit" class="btn-next">Salvar Alterações</button>
            
            <a href="gerenciar-aulas.php?curso_id=<?php echo $curso_id; ?>" class="back-link">Voltar para Gerenciar Aulas</a>
        </form>
    </div>
</body>
</html>