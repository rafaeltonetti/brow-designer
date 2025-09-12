<?php
session_start();
include 'conexao.php';

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o ID da aula e do curso foram passados na URL
if (!isset($_GET['aula_id']) || !isset($_GET['curso_id'])) {
    header("Location: cursos.php");
    exit();
}

$aula_id = $_GET['aula_id'];
$curso_id = $_GET['curso_id'];
$id_usuario = $_SESSION['id_usuario'];

// Busca as informações da aula atual
$stmt_aula = $conn->prepare("SELECT titulo, descricao, video_url FROM aulas WHERE id = ?");
$stmt_aula->bind_param("i", $aula_id);
$stmt_aula->execute();
$result_aula = $stmt_aula->get_result();
$aula = $result_aula->fetch_assoc();

if (!$aula) {
    echo "Aula não encontrada!";
    exit();
}

// Busca todas as aulas do curso para o menu lateral
$stmt_aulas = $conn->prepare("SELECT id, titulo FROM aulas WHERE curso_id = ? ORDER BY id ASC");
$stmt_aulas->bind_param("i", $curso_id);
$stmt_aulas->execute();
$result_aulas = $stmt_aulas->get_result();

// Busca as aulas que o usuário já concluiu
$aulas_concluidas = [];
$stmt_concluidas = $conn->prepare("SELECT id_aula FROM aulas_concluidas WHERE id_usuario = ?");
$stmt_concluidas->bind_param("i", $id_usuario);
$stmt_concluidas->execute();
$result_concluidas = $stmt_concluidas->get_result();
while ($row = $result_concluidas->fetch_assoc()) {
    $aulas_concluidas[] = $row['id_aula'];
}
$stmt_concluidas->close();

// Função para extrair o ID do vídeo do YouTube
function get_youtube_id($url) {
    $url_parts = parse_url($url);
    if (isset($url_parts['host'])) {
        $host = strtolower($url_parts['host']);
        if ($host == 'www.youtube.com' || $host == 'youtube.com') {
            if (isset($url_parts['query'])) {
                parse_str($url_parts['query'], $query_parts);
                if (isset($query_parts['v'])) {
                    return $query_parts['v'];
                }
            }
        } elseif ($host == 'youtu.be') {
            if (isset($url_parts['path'])) {
                return trim($url_parts['path'], '/');
            }
        }
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($aula['titulo']); ?> - Grow Cursos</title>
    <link rel="stylesheet" href="css/curso.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

    <header>
        <nav id="navbar">
            <div class="navbar">
                <a href="index.php" class="logo">BROW CURSOS</a>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cursos.php">Cursos</a></li>
                    <li><a href="certificados.php">Certificados</a></li>
                </ul>

                <div class="user-menu">
                    <div class="avatar" id="avatar">
                        <img src="img/perfil.png" alt="Foto de Perfil">
                    </div>
                    <div class="dropdown" id="menu">
                        <a href="userpage.php">Perfil</a>
                        <a href="certificados.php">Certificados</a>
                        <a href="ajuda.php">Ajuda</a>
                        <hr>
                        <a href="logout.php">Sair</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="aula-main">
        <section class="video-content">
            <h1><?php echo htmlspecialchars($aula['titulo']); ?></h1>
            
            <div class="video-player">
                <?php $youtube_id = get_youtube_id($aula['video_url']); ?>
                <?php if ($youtube_id): ?>
                    <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($youtube_id); ?>" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <p>Vídeo da aula não disponível. Verifique a URL do vídeo.</p>
                <?php endif; ?>
            </div>

            <div class="aula-descricao">
                <h2>Descrição da Aula</h2>
                <p><?php echo htmlspecialchars($aula['descricao']); ?></p>
            </div>
        </section>

        <aside class="sidebar-aulas">
            <h2>Aulas do Curso</h2>
            <ul class="aulas-list">
                <?php
                $result_aulas->data_seek(0);
                while ($aula_sidebar = $result_aulas->fetch_assoc()):
                    $aula_concluida = in_array($aula_sidebar['id'], $aulas_concluidas);
                ?>
                    <li class="<?php echo ($aula_sidebar['id'] == $aula_id) ? 'active' : ''; ?>">
                        <label class="custom-checkbox-container">
                            <input type="checkbox"
                                   class="aula-checkbox"
                                   data-aula-id="<?php echo htmlspecialchars($aula_sidebar['id']); ?>"
                                   <?php echo $aula_concluida ? 'checked' : ''; ?>>
                            <span class="checkmark"></span>
                            <a href="aula.php?aula_id=<?php echo htmlspecialchars($aula_sidebar['id']); ?>&curso_id=<?php echo htmlspecialchars($curso_id); ?>">
                                <?php echo htmlspecialchars($aula_sidebar['titulo']); ?>
                            </a>
                        </label>
                    </li>
                <?php endwhile; ?>
            </ul>
        </aside>
    </main>

    <footer>
        <p>&copy; 2025 Grow Cursos</p>
    </footer>

    <script src="js/user_menu.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxes = document.querySelectorAll('.aula-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', (event) => {
                    const aulaId = event.target.dataset.aulaId;
                    const isChecked = event.target.checked;

                    // Chama o arquivo PHP via AJAX
                    fetch('marcar_aula_concluida.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id_aula=${aulaId}&concluido=${isChecked}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Status da aula atualizado com sucesso!');
                        } else {
                            console.error('Erro ao atualizar status da aula:', data.message);
                            event.target.checked = !isChecked; // Reverte a mudança do checkbox
                        }
                    })
                    .catch(error => {
                        console.error('Erro na requisição:', error);
                        event.target.checked = !isChecked; // Reverte a mudança do checkbox
                    });
                });
            });
        });
    </script>
</body>
</html>

<?php
$stmt_aula->close();
$stmt_aulas->close();
$conn->close();
?>