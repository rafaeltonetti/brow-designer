<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Alunos</title>
    <link rel="stylesheet" href="css/gerenciar-alunos.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="top-header">
        <div class="logo">BROW CURSOS</div>
        <a href="admin.php" class="back-link">&larr; Voltar para o Admin</a>
    </header>

    <div class="container">
        <h1 class="page-title">Gerenciar Alunos</h1>
        
        <div class="student-list">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Maria da Silva</td>
                        <td>maria.silva@email.com</td>
                        <td>
                            <a href="editar-aluno.php?id=1" class="btn-action btn-details">Ver Detalhes</a>
                            <a href="adicionar-certificado.php?student_id=1" class="btn-action btn-certificate">Adicionar Certificado</a>
                        </td>
                    </tr>
                    <tr>
                        <td>João Oliveira</td>
                        <td>joao.oliver@email.com</td>
                        <td>
                            <a href="editar-aluno.php?id=2" class="btn-action btn-details">Ver Detalhes</a>
                            <a href="adicionar-certificado.php?student_id=2" class="btn-action btn-certificate">Adicionar Certificado</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Ana Souza</td>
                        <td>ana.souza@email.com</td>
                        <td>
                            <a href="editar-aluno.php?id=3" class="btn-action btn-details">Ver Detalhes</a>
                            <a href="adicionar-certificado.php?student_id=3" class="btn-action btn-certificate">Adicionar Certificado</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>