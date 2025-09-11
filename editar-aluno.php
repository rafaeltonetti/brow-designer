<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno</title>
    <link rel="stylesheet" href="css/editar-aluno.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="top-header">
        <div class="logo">BROW CURSOS</div>
        <a href="GerenciarAlunos.php" class="back-link">&larr; Voltar para Alunos</a>
    </header>

    <div class="container">
        <h1 class="page-title">Dados do Aluno</h1>
        
        <div class="aluno-details-card">
            <h2 class="section-heading">Informações Pessoais</h2>
            <div class="info-group">
                <p><strong>Nome:</strong> Maria da Silva</p>
                <p><strong>E-mail:</strong> maria.silva@email.com</p>
                <p><strong>Telefone:</strong> (11) 98765-4321</p>
                <p><strong>CPF:</strong> 123.456.789-00</p>
            </div>
            <a href="#" class="btn-edit">Editar Dados</a>
        </div>

        <h1 class="page-title">Cursos do Aluno</h1>

        <div class="courses-list-card">
            <div class="course-item">
                <div class="course-info">
                    <img src="course-placeholder-1.jpg" alt="Capa do Curso" class="course-cover">
                    <div class="course-text">
                        <h3>O Novo Método Mulher Milionária</h3>
                        <p class="status liberado">Liberado</p>
                    </div>
                </div>
                <a href="adicionar-certificado.php?student_id=1&course_id=1" class="btn-cert">Adicionar Certificado</a>
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
</body>
</html>