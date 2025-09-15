<?php
include("conexao.php");

$sql = "SELECT id, nome, cpf, telefone, email FROM usuarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<form method='POST' action='processa_aluno.php'>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='aluno-item'>";
        echo "<input type='checkbox' name='alunos[]' value='" . $row['id'] . "'>";
        echo "<label>";
        echo "<strong>" . $row['nome'] . "</strong><br>";
        echo "CPF: " . $row['cpf'] . "<br>";
        echo "Telefone: " . $row['telefone'] . "<br>";
        echo "E-mail: " . $row['email'];
        echo "</label>";
        echo "</div>";
    }
    echo "<button type='submit'>Selecionar</button>";
    echo "</form>";
} else {
    echo "Nenhum aluno cadastrado.";
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Aluno - <?php echo htmlspecialchars($aluno['nome']); ?></title>
    <link rel="stylesheet" href="css/aluno_detalhe.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>

    <header id="navbar">
        <div class="navbar">
            <a href="index.php" class="logo">BROW CURSOS</a>
            <ul>
                <li><a href="gerenciar_alunos.php">Voltar para Gerenciar Alunos</a></li>
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
        <div class="aluno-detalhe-card">
            <a href="gerenciar_alunos.php" class="back-link">&larr; Voltar</a>
            <h1>Detalhes do Aluno</h1>
            <div class="aluno-info">
                <h3>Nome:</h3>
                <p><?php echo htmlspecialchars($aluno['nome']); ?></p>
                <h3>E-mail:</h3>
                <p><?php echo htmlspecialchars($aluno['email']); ?></p>
            </div>

            <div class="cursos-info">
                <h2>Cursos Matriculados</h2>
                <?php if ($result_cursos->num_rows > 0): ?>
                    <ul>
                        <?php while ($curso = $result_cursos->fetch_assoc()): ?>
                            <li><?php echo htmlspecialchars($curso['nome']); ?></li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>O aluno não está matriculado em nenhum curso.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> BROW CURSOS. Todos os direitos reservados.
    </footer>

    <script src="js/main.js"></script>

</body>

</html>