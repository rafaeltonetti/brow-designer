<?php
session_start();
include 'conexao.php';

// **VERIFICAÇÃO DE SEGURANÇA**
// Redireciona para o login se o usuário não for um administrador (exemplo: nivel_acesso = 1)
// Por favor, ajuste o nome da coluna e o valor conforme a sua tabela
if (!isset($_SESSION['id_usuario']) || $_SESSION['is_admin'] != 1) {
    header("Location: index.php");
    exit();
}

// Busca todos os usuários (alunos) do banco de dados
try {
    // A consulta seleciona o ID, nome e e-mail da tabela 'usuarios'
    // Você pode adicionar uma cláusula WHERE se tiver uma coluna para filtrar alunos e administradores
    $stmt = $conn->prepare("SELECT id, nome, email FROM usuarios");
    $stmt->execute();
    $result_alunos = $stmt->get_result();

} catch (Exception $e) {
    die("Erro ao buscar alunos: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Alunos - BROW CURSOS</title>
    <link rel="stylesheet" href="css/gerenciar_alunos.css">
    <link rel="stylesheet" href="css/main.css"> </head>
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

    <div class="admin-container">
        <h1>Gerenciar Alunos</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_alunos->num_rows > 0): ?>
                        <?php while ($aluno = $result_alunos->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                                <td><?php echo htmlspecialchars($aluno['email']); ?></td>
                                <td class="actions">
                                    <a href="editar-aluno.php?id=<?php echo $aluno['id']; ?>" class="btn-details">Ver Detalhes</a>
                                    <a href="#" class="btn-cert">Adicionar Certificado</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="no-records">Nenhum aluno encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> BROW CURSOS. Todos os direitos reservados.
    </footer>
    
    <script src="js/main.js"></script>
</body>
</html>