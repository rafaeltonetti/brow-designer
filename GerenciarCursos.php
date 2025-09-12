<?php
session_start();
include 'conexao.php';

// Verifica se o usuário é um admin logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Lógica para excluir um curso
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Prepara a query para deletar o curso
    $stmt_delete = $conn->prepare("DELETE FROM cursos WHERE id = ?");
    $stmt_delete->bind_param("i", $delete_id);
    
    if ($stmt_delete->execute()) {
        // Exclui também as aulas relacionadas (opcional, mas recomendado)
        $stmt_delete_aulas = $conn->prepare("DELETE FROM aulas WHERE curso_id = ?");
        $stmt_delete_aulas->bind_param("i", $delete_id);
        $stmt_delete_aulas->execute();
        
        echo "<script>alert('Curso excluído com sucesso!');</script>";
        header("Location: GerenciarCursos.php"); // Redireciona para evitar re-exclusão
        exit();
    } else {
        echo "<script>alert('Erro ao excluir o curso: " . $stmt_delete->error . "');</script>";
    }
    
    $stmt_delete->close();
}

// Prepara a query para buscar todos os cursos
$stmt = $conn->prepare("SELECT id, nome, descricao, capa FROM cursos ORDER BY nome ASC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Cursos - BROW CURSOS</title>
    <link rel="stylesheet" href="css/gerenciar-cursos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark pt-4 pb-4 mb-4">
        <div class="container">
        <a class="navbar-brand" href="#">Brow Designer</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="#">Botão 1</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Botão 2</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Botão 3</a></li>
            </ul>
        </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="page-title">Gerenciar Cursos</h1>
        
        <div class="course-list">

            <?php if ($result->num_rows > 0): ?>
                <?php while ($curso = $result->fetch_assoc()): ?>
                    <div class="course-card">
                        <div class="course-info">
                            <img src="<?php echo htmlspecialchars($curso['capa']); ?>" alt="Capa do Curso" class="course-cover">
                            <div class="info-text">
                                <h3><?php echo htmlspecialchars($curso['nome']); ?></h3>
                                <p><?php echo htmlspecialchars(substr($curso['descricao'], 0, 100)); ?>...</p>
                            </div>
                        </div>
                        <div class="course-actions">
                            <a href="gerenciar-aulas.php?curso_id=<?php echo $curso['id']; ?>" class="btn-action">Gerenciar Aulas</a>
                            <a href="editar-curso.php?id=<?php echo $curso['id']; ?>" class="btn-action">Editar</a>
                            <a href="GerenciarCursos.php?delete_id=<?php echo $curso['id']; ?>" class="btn-action btn-danger" onclick="return confirm('Tem certeza que deseja excluir este curso?');">Excluir</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhum curso cadastrado ainda.</p>
            <?php endif; ?>

            <div class="add-course-btn-container">
                <a href="AddCurso.php" class="btn-add-new-course">+ Adicionar Novo Curso</a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>