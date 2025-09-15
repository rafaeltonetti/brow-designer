<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

try {
    $stmt = $conn->prepare("
        SELECT 
            c.id, 
            c.nome, 
            c.descricao
        FROM 
            certificados cert
        JOIN
            cursos c ON cert.curso_id = c.id
        WHERE 
            cert.usuario_id = ?
    ");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result_certificados = $stmt->get_result();
} catch (Exception $e) {
    die("Erro ao buscar certificados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Certificados - BROW CURSOS</title>
    <link rel="stylesheet" href="css/certificados.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

    <header id="navbar">
        <div class="navbar">
            <a href="index.php" class="logo">BROW CURSOS</a>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="cursos.php">Cursos</a></li>
                <li><a href="certificados.php">Certificados</a></li>
                <li class="user-menu">
                    <div class="avatar">
                        <img src="https://via.placeholder.com/40" alt="Avatar">
                    </div>
                    <div class="dropdown">
                        <a href="userpage.php">Meu Perfil</a>
                        <hr>
                        <a href="logout.php">Sair</a>
                    </div>
                </li>
            </ul>
        </div>
    </header>

    <div class="main-container">
        <h1>Meus Certificados</h1>
        <p class="subtitle">Parabéns por completar seus cursos! Baixe seus certificados aqui.</p>

        <?php if ($result_certificados->num_rows > 0): ?>
            <div class="certificados-grid">
                <?php while ($certificado = $result_certificados->fetch_assoc()): ?>
                    <div class="certificado-card">
                        <div class="card-content">
                            <h3><?php echo htmlspecialchars($certificado['titulo']); ?></h3>
                            <p><?php echo htmlspecialchars($certificado['descricao']); ?></p>
                        </div>
                        <a href="gerar_certificado.php?curso_id=<?php echo $certificado['id']; ?>" class="download-btn" target="_blank">
                            Baixar Certificado
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>Nenhum certificado disponível ainda.</p>
                <p>Complete um curso para obtê-lo!</p>
                <a href="cursos.php" class="cta-link">Ver Cursos Disponíveis</a>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> BROW CURSOS. Todos os direitos reservados.
    </footer>

    <script src="js/main.js"></script>

</body>

</html>