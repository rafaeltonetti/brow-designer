<?php
session_start();
include 'conexao.php';

header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}

// Verifica se a requisição é um POST e se os dados necessários foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_aula']) && isset($_POST['concluido'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $id_aula = $_POST['id_aula'];
    $concluido = $_POST['concluido'] === 'true';

    if ($concluido) {
        // Marcar a aula como concluída: insere o registro.
        // O ON DUPLICATE KEY UPDATE evita erros se o registro já existir.
        $stmt = $conn->prepare("INSERT INTO aulas_concluidas (id_usuario, id_aula) VALUES (?, ?) ON DUPLICATE KEY UPDATE id_aula = id_aula");
        $stmt->bind_param("ii", $id_usuario, $id_aula);
    } else {
        // Desmarcar a aula: deleta o registro.
        $stmt = $conn->prepare("DELETE FROM aulas_concluidas WHERE id_usuario = ? AND id_aula = ?");
        $stmt->bind_param("ii", $id_usuario, $id_aula);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o banco de dados.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
}
?>