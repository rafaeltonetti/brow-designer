<?php
session_start();
include 'conexao.php';

// Verifica se o usuário é um admin logado
if (!isset($_SESSION['id_usuario']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

$curso_id = null;

// Verifica se o ID do curso foi passado na URL
if (isset($_GET['id'])) {
    $curso_id = $_GET['id'];
} else {
    // Redireciona se o ID não for especificado
    header("Location: GerenciarCursos.php");
    exit();
}

// Lógica para buscar os dados do curso
$stmt = $conn->prepare("SELECT id, nome, descricao, capa FROM cursos WHERE id = ?");
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Curso não encontrado.'); window.location.href='GerenciarCursos.php';</script>";
    exit();
}

$curso = $result->fetch_assoc();

// Lógica para processar a atualização do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novo_nome = $_POST['course-name'];
    $nova_descricao = $_POST['course-description'];
    $nova_capa = $curso['capa']; // Mantém a capa atual por padrão

    // Processamento da nova imagem de capa
    if (isset($_FILES['course-cover']) && $_FILES['course-cover']['error'] == 0) {
        $upload_dir = 'uploads/capas/';
        
        // Verifica se a pasta existe
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $nome_arquivo = basename($_FILES['course-cover']['name']);
        $caminho_arquivo = $upload_dir . uniqid() . '_' . $nome_arquivo;
        
        if (move_uploaded_file($_FILES['course-cover']['tmp_name'], $caminho_arquivo)) {
            $nova_capa = $caminho_arquivo;
            
            // Opcional: Deleta a capa antiga para não acumular arquivos
            if (file_exists($curso['capa']) && $curso['capa'] != 'uploads/capas/default.jpg') { // Altere 'default.jpg' para o nome da sua capa padrão
                unlink($curso['capa']);
            }
        }
    }

    // Atualiza os dados no banco de dados
    $stmt_update = $conn->prepare("UPDATE cursos SET nome = ?, descricao = ?, capa = ? WHERE id = ?");
    $stmt_update->bind_param("sssi", $novo_nome, $nova_descricao, $nova_capa, $curso_id);
    
    if ($stmt_update->execute()) {
        echo "<script>alert('Curso atualizado com sucesso!'); window.location.href='GerenciarCursos.php';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao atualizar o curso: " . $stmt_update->error . "');</script>";
    }
    $stmt_update->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso - <?php echo htmlspecialchars($curso['nome']); ?></title>
    <link rel="stylesheet" href="css/add-curso.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <div class="logo">
            BROW CURSOS
        </div>
        
        <h1 class="page-title">Editar Curso</h1>
        
        <form action="editar-curso.php?id=<?php echo $curso_id; ?>" method="POST" class="add-course-form" enctype="multipart/form-data">
            
            <div class="input-group">
                <label for="course-name">Nome do Curso</label>
                <input type="text" id="course-name" name="course-name" value="<?php echo htmlspecialchars($curso['nome']); ?>" required>
            </div>
            
            <div class="input-group">
                <label for="course-description">Sobre o Curso</label>
                <textarea id="course-description" name="course-description" rows="5" required><?php echo htmlspecialchars($curso['descricao']); ?></textarea>
            </div>

            <div class="input-group file-upload-group">
                <label for="course-cover">Capa do Curso</label>
                <div class="current-cover">
                    <p>Capa atual:</p>
                    <img src="<?php echo htmlspecialchars($curso['capa']); ?>" alt="Capa atual" class="cover-preview">
                </div>
                <div class="custom-file-upload">
                    <input type="file" id="course-cover" name="course-cover" accept="image/*" onchange="displayFileName(this)">
                    <span id="file-name">Escolha uma nova imagem</span>
                </div>
                <p class="file-instruction">Clique para escolher uma imagem ou arraste e solte aqui.</p>
            </div>
            
            <button type="submit" class="btn-next">Salvar Alterações</button>
            
            <a href="GerenciarCursos.php" class="back-link">Voltar para Gerenciar Cursos</a>
        </form>
    </div>

    <script>
        function displayFileName(input) {
            var fileName = input.files[0] ? input.files[0].name : "Nenhuma imagem selecionada";
            document.getElementById('file-name').textContent = fileName;
        }
    </script>
</body>
</html>