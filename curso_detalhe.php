<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o ID do curso foi passado na URL
if (!isset($_GET['curso_id']) || !is_numeric($_GET['curso_id'])) {
    header("Location: cursos.php");
    exit();
}

$curso_id = $_GET['curso_id'];

// Lógica para buscar os dados do curso
$stmt_curso = $conn->prepare("SELECT nome, descricao FROM cursos WHERE id = ?");
$stmt_curso->bind_param("i", $curso_id);
$stmt_curso->execute();
$result_curso = $stmt_curso->get_result();

if ($result_curso->num_rows === 0) {
    echo "<script>alert('Curso não encontrado.'); window.location.href='cursos.php';</script>";
    exit();
}

$curso = $result_curso->fetch_assoc();

// Lógica para buscar todas as aulas do curso
$stmt_aulas = $conn->prepare("SELECT id, titulo, descricao, video_url FROM aulas WHERE curso_id = ? ORDER BY id ASC");
$stmt_aulas->bind_param("i", $curso_id);
$stmt_aulas->execute();
$result_aulas = $stmt_aulas->get_result();

// Busca a primeira aula para exibir no player principal
$primeira_aula = $result_aulas->fetch_assoc();
$result_aulas->data_seek(0); // Volta o ponteiro para o início do resultado para o loop

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($curso['nome']); ?></title>
    <link rel="stylesheet" href="css/curso_detalhe.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="top-header">
        <div class="logo">BROW CURSOS</div>
        <nav class="user-nav">
            <a href="cursos.php">Todos os Cursos</a>
            <a href="userpage.php">Meu Perfil</a>
        </nav>
    </header>

    <div class="course-container">
        <div class="main-content">
            <h1 class="course-title"><?php echo htmlspecialchars($curso['nome']); ?></h1>
            <p class="course-description"><?php echo htmlspecialchars($curso['descricao']); ?></p>

            <div class="video-player">
                <?php if ($primeira_aula): ?>
                    <video id="video-aula" controls src="<?php echo htmlspecialchars($primeira_aula['video_url']); ?>" class="responsive-video"></video>
                <?php else: ?>
                    <div class="no-video">Nenhuma aula disponível para este curso.</div>
                <?php endif; ?>
            </div>
            <h2 id="aula-titulo"><?php echo $primeira_aula ? htmlspecialchars($primeira_aula['titulo']) : 'Nenhuma aula selecionada'; ?></h2>
            <p id="aula-descricao"><?php echo $primeira_aula ? htmlspecialchars($primeira_aula['descricao']) : ''; ?></p>
        </div>

        <aside class="sidebar-aulas">
            <h2 class="sidebar-title">Aulas do Curso</h2>
            <ul class="lista-aulas">
                <?php while ($aula = $result_aulas->fetch_assoc()): ?>
                    <li class="aula-item" data-video-url="<?php echo htmlspecialchars($aula['video_url']); ?>" data-titulo="<?php echo htmlspecialchars($aula['titulo']); ?>" data-descricao="<?php echo htmlspecialchars($aula['descricao']); ?>">
                        <span class="aula-titulo"><?php echo htmlspecialchars($aula['titulo']); ?></span>
                    </li>
                <?php endwhile; ?>
            </ul>
        </aside>
    </div>

    <script>
        document.querySelectorAll('.aula-item').forEach(item => {
            item.addEventListener('click', function() {
                const videoPlayer = document.getElementById('video-aula');
                const aulaTitulo = document.getElementById('aula-titulo');
                const aulaDescricao = document.getElementById('aula-descricao');
                
                // Atualiza o src do player de vídeo e o título/descrição
                videoPlayer.src = this.dataset.videoUrl;
                aulaTitulo.textContent = this.dataset.titulo;
                aulaDescricao.textContent = this.dataset.descricao;
            });
        });
    </script>
</body>
</html>
<?php
$stmt_curso->close();
$stmt_aulas->close();
$conn->close();
?>