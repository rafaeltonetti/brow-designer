<?php
session_start();
include 'conexao.php';

// Redireciona se o usuário não estiver logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['curso_id'])) {
    header("Location: cursos.php");
    exit();
}

$curso_id = intval($_GET['curso_id']);
$id_usuario = intval($_SESSION['id_usuario']);

// Se veio aula_id na URL, usa ela; senão, pega a primeira aula do curso
$aula_id_get = isset($_GET['aula_id']) ? intval($_GET['aula_id']) : null;

if ($aula_id_get) {
    $stmt_aula = $conn->prepare("SELECT id, titulo, descricao, video_url FROM aulas WHERE curso_id = ? AND id = ? LIMIT 1");
    $stmt_aula->bind_param("ii", $curso_id, $aula_id_get);
    $stmt_aula->execute();
    $result_aula = $stmt_aula->get_result();
    $primeira_aula = $result_aula->fetch_assoc();
    $stmt_aula->close();

    // Se por algum motivo não encontrou (id inválido), pegar a primeira
    if (!$primeira_aula) {
        $stmt_primeira_aula = $conn->prepare("SELECT id, titulo, descricao, video_url FROM aulas WHERE curso_id = ? ORDER BY id ASC LIMIT 1");
        $stmt_primeira_aula->bind_param("i", $curso_id);
        $stmt_primeira_aula->execute();
        $result_primeira_aula = $stmt_primeira_aula->get_result();
        $primeira_aula = $result_primeira_aula->fetch_assoc();
        $stmt_primeira_aula->close();
    }
} else {
    $stmt_primeira_aula = $conn->prepare("SELECT id, titulo, descricao, video_url FROM aulas WHERE curso_id = ? ORDER BY id ASC LIMIT 1");
    $stmt_primeira_aula->bind_param("i", $curso_id);
    $stmt_primeira_aula->execute();
    $result_primeira_aula = $stmt_primeira_aula->get_result();
    $primeira_aula = $result_primeira_aula->fetch_assoc();
    $stmt_primeira_aula->close();
}

if (!$primeira_aula) {
    echo "Nenhuma aula encontrada para este curso!";
    exit();
}

$aula_atual_id = intval($primeira_aula['id']);

// Busca todas as aulas do curso para o menu lateral
$stmt_aulas = $conn->prepare("SELECT id, titulo FROM aulas WHERE curso_id = ? ORDER BY id ASC");
$stmt_aulas->bind_param("i", $curso_id);
$stmt_aulas->execute();
$result_aulas = $stmt_aulas->get_result();

// Busca as aulas que o usuário já concluiu
$aulas_concluidas = [];
$stmt_concluidas = $conn->prepare("SELECT aula_id FROM aulas_concluidas WHERE usuario_id = ?");
$stmt_concluidas->bind_param("i", $id_usuario);
$stmt_concluidas->execute();
$result_concluidas = $stmt_concluidas->get_result();
while ($row = $result_concluidas->fetch_assoc()) {
    $aulas_concluidas[] = intval($row['aula_id']);
}
$stmt_concluidas->close();

// Função para extrair o ID do vídeo do YouTube
function get_youtube_id($url) {
    if (!$url) return false;
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
    <title><?php echo htmlspecialchars($primeira_aula['titulo']); ?> - Grow Cursos</title>
    <link rel="stylesheet" href="css/curso_detalhe.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
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

    <main class="aula-main">
        <section class="video-content">
            <h1><?php echo htmlspecialchars($primeira_aula['titulo']); ?></h1>
            
            <div class="video-player">
                <?php $youtube_id = get_youtube_id($primeira_aula['video_url']); ?>
                <?php if ($youtube_id): ?>
                    <iframe src="https://www.youtube.com/embed/<?php echo htmlspecialchars($youtube_id); ?>" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <p>Vídeo da aula não disponível. Verifique a URL do vídeo.</p>
                <?php endif; ?>
            </div>

            <div class="aula-descricao">
                <h2>Descrição da Aula</h2>
                <p><?php echo nl2br(htmlspecialchars($primeira_aula['descricao'])); ?></p>
            </div>
        </section>

        <aside class="sidebar-aulas">
            <h2>Aulas do Curso</h2>
            <ul class="aulas-list">
                <?php
                $result_aulas->data_seek(0);
                while ($aula_sidebar = $result_aulas->fetch_assoc()):
                    $aula_concluida = in_array($aula_sidebar['id'], $aulas_concluidas);
                    $is_active = ($aula_sidebar['id'] == $aula_atual_id) ? 'active' : '';
                ?>
                    <li class="<?php echo $is_active; ?>">
                        <a href="curso_detalhe.php?curso_id=<?php echo htmlspecialchars($curso_id); ?>&aula_id=<?php echo htmlspecialchars($aula_sidebar['id']); ?>" class="aula-item-container">
                            <input type="checkbox"
                                   class="aula-checkbox"
                                   data-aula-id="<?php echo htmlspecialchars($aula_sidebar['id']); ?>"
                                   <?php echo $aula_concluida ? 'checked' : ''; ?>>
                            <div class="custom-checkbox"></div>
                            <span class="aula-title"><?php echo htmlspecialchars($aula_sidebar['titulo']); ?></span>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </aside>
    </main>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    // --- LÓGICA DO MENU DROPDOWN (AVATAR) ---
    const userMenu = document.querySelector('.user-menu');
    const avatar = document.querySelector('.avatar');

    avatar.addEventListener('click', (event) => {
        // Impede que o clique se propague para o documento, o que fecharia o menu imediatamente
        event.stopPropagation();
        userMenu.classList.toggle('active');
    });

    // Fecha o menu se o usuário clicar em qualquer lugar fora dele
    document.addEventListener('click', (event) => {
        if (!userMenu.contains(event.target)) {
            userMenu.classList.remove('active');
        }
    });

    // --- LÓGICA DO CHECKBOX DA BARRA LATERAL ---
    const aulasList = document.querySelector('.aulas-list');

    aulasList.addEventListener('click', (event) => {
        const target = event.target;
        const linkContainer = target.closest('.aula-item-container');

        if (linkContainer) {
            const checkbox = linkContainer.querySelector('.aula-checkbox');

            if (target !== checkbox) {
                event.preventDefault();
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
    });

    const checkboxes = document.querySelectorAll('.aula-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', (event) => {
            const el = event.target;
            const aulaId = el.dataset.aulaId;
            const isChecked = el.checked;

            fetch('marcar_aula_concluida.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `aula_id=${encodeURIComponent(aulaId)}&concluido=${encodeURIComponent(isChecked ? 1 : 0)}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const listItem = el.closest('li');
                    if (isChecked) {
                        listItem.classList.add('active');
                    } else {
                        listItem.classList.remove('active');
                    }
                    console.log('Status da aula atualizado com sucesso!');
                } else {
                    console.error('Erro ao atualizar status da aula:', data.message);
                    el.checked = !isChecked;
                    alert('Erro ao atualizar a aula. Tente novamente.');
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                el.checked = !isChecked;
                alert('Erro de rede ao tentar atualizar. Verifique sua conexão.');
            });
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt_aulas->close();
$conn->close();
?>