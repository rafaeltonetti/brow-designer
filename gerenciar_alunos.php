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
    <link rel="stylesheet" href="css/main.css">
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
        <center>
            <h1 class="page-title">Gerenciar Alunos</h1>
        </center>

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
    
    <script src="js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>