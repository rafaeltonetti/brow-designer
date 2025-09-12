<?php
session_start();
include 'conexao.php';

// Redireciona para o login se o usuário não estiver logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Lógica para buscar todos os cursos
$stmt = $conn->prepare("SELECT id, nome, descricao, capa FROM cursos ORDER BY nome ASC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos Disponíveis</title>
    <link rel="stylesheet" href="css/cursos.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="top-header">
        <div class="logo">BROW CURSOS</div>
        <?php
// Extrai o primeiro nome do nome completo
$nome_completo = $_SESSION['nome_usuario'];
$primeiro_nome = explode(" ", $nome_completo)[0];
?>

<div class="user-info">
    <a href="userpage.php">Olá, <?php echo htmlspecialchars($primeiro_nome); ?></a>
</div>
    </header>

    <div class="container">
        <h1 class="page-title">Cursos Disponíveis</h1>
        
        <div class="course-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($curso = $result->fetch_assoc()): ?>
                    <a href="curso_detalhe.php?curso_id=<?php echo $curso['id']; ?>" class="course-card">
                        <img src="<?php echo htmlspecialchars($curso['capa']); ?>" alt="Capa do Curso" class="course-cover">
                        <div class="course-info">
                            <h3><?php echo htmlspecialchars($curso['nome']); ?></h3>
                            <p><?php echo htmlspecialchars(substr($curso['descricao'], 0, 100)); ?>...</p>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Nenhum curso disponível no momento.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>