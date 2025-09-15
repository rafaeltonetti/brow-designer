<?php
include("conexao.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT nome, email, telefone, cpf FROM usuarios WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $aluno = $result->fetch_assoc();
    } else {
        die("Aluno não encontrado.");
    }
} else {
    die("ID do aluno não informado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno</title>
    <link rel="stylesheet" href="css/editar-aluno.css">
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

    <div class="container-site">
        <h1 class="page-title">Dados do Aluno</h1>

        <!-- Card com dados reais do aluno -->
        <div class="aluno-details-card">
            <h3 class="section-heading">Informações Pessoais</h3>
            <div class="info-group">
                <p><strong>Nome:</strong> <?php echo $aluno['nome']; ?></p>
                <p><strong>E-mail:</strong> <?php echo $aluno['email']; ?></p>
                <p><strong>Telefone:</strong> <?php echo $aluno['telefone']; ?></p>
                <p><strong>CPF:</strong> <?php echo $aluno['cpf']; ?></p>
            </div>
            <a href="editar-dados.php?id=<?php echo $id; ?>" class="btn-edit">Editar Dados</a>
        </div>

        <h1 class="page-title">Cursos do Aluno</h1>

        <!-- Cursos (ainda tá fixo, mas dá pra puxar do banco depois) -->
        <div class="courses-list-card">
            <div class="course-item">
                <div class="course-info">
                    <img src="course-placeholder-1.jpg" alt="Capa do Curso" class="course-cover">
                    <div class="course-text">
                        <h3>O Novo Método Mulher Milionária</h3>
                        <p class="status liberado">Liberado</p>
                    </div>
                </div>
                <a href="adicionar-certificado.php?student_id=<?php echo $id; ?>&course_id=1" class="btn-cert">Adicionar Certificado</a>
            </div>

            <div class="course-item">
                <div class="course-info">
                    <img src="course-placeholder-2.jpg" alt="Capa do Curso" class="course-cover">
                    <div class="course-text">
                        <h3>Jornada de Maquiagem</h3>
                        <p class="status nao-liberado">Disponível em 3.7%</p>
                    </div>
                </div>
                <a href="#" class="btn-cert disabled">Certificado Indisponível</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>