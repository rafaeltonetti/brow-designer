<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

$curso_id = null;
$curso_nome = "Curso não encontrado";
$encontrado = false;

if (isset($_GET['curso_id']) && is_numeric($_GET['curso_id'])) {
    $curso_id = $_GET['curso_id'];

    $stmt_curso = $conn->prepare("SELECT nome FROM cursos WHERE id = ?");
    $stmt_curso->bind_param("i", $curso_id);
    $stmt_curso->execute();
    $result_curso = $stmt_curso->get_result();

    if ($result_curso->num_rows > 0) {
        $curso = $result_curso->fetch_assoc();
        $curso_nome = $curso['nome'];
        $encontrado = true;
    }
    $stmt_curso->close();
}

if (!$encontrado) {
    header("Location: GerenciarCursos.php");
    exit();
}

if (isset($_GET['delete_aula_id'])) {
    $delete_aula_id = $_GET['delete_aula_id'];

    $stmt_delete = $conn->prepare("DELETE FROM aulas WHERE id = ? AND curso_id = ?");
    $stmt_delete->bind_param("ii", $delete_aula_id, $curso_id);

    if ($stmt_delete->execute()) {
        echo "<script>alert('Aula excluída com sucesso!');</script>";
        header("Location: gerenciar-aulas.php?curso_id=" . $curso_id);
        exit();
    } else {
        echo "<script>alert('Erro ao excluir aula.');</script>";
    }
    $stmt_delete->close();
}

$stmt_aulas = $conn->prepare("SELECT id, titulo, descricao FROM aulas WHERE curso_id = ? ORDER BY id ASC");
$stmt_aulas->bind_param("i", $curso_id);
$stmt_aulas->execute();
$result_aulas = $stmt_aulas->get_result();

$stmt_aulas->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Aulas - <?php echo htmlspecialchars($curso_nome); ?></title>
    <link rel="stylesheet" href="css/gerenciar-aulas.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
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

    <div class="site-container">
        <h1 class="page-title">Gerenciar Aulas</h1>
        <h2 class="course-subtitle">Curso: <?php echo htmlspecialchars($curso_nome); ?></h2>

        <div class="aula-list">
            <?php if ($result_aulas->num_rows > 0): ?>
                <?php while ($aula = $result_aulas->fetch_assoc()): ?>
                    <div class="aula-card">
                        <div class="aula-info">
                            <h3><?php echo htmlspecialchars($aula['titulo']); ?></h3>
                            <p><?php echo htmlspecialchars($aula['descricao']); ?></p>
                        </div>
                        <div class="aula-actions">
                            <a href="editar-aula.php?aula_id=<?php echo $aula['id']; ?>&curso_id=<?php echo $curso_id; ?>" class="btn-action">Editar</a>
                            <a href="gerenciar-aulas.php?curso_id=<?php echo $curso_id; ?>&delete_aula_id=<?php echo $aula['id']; ?>" class="btn-action btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta aula?');">Excluir</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhuma aula cadastrada ainda. <a href="aulas.php?course_id=<?php echo $curso_id; ?>" class="add-aula-link">Adicionar uma aula.</a></p>
            <?php endif; ?>
        </div>

        <div class="add-aula-btn-container">
            <a href="aulas.php?course_id=<?php echo $curso_id; ?>" class="btn-add-new-aula">+ Adicionar Nova Aula</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>