<?php
session_start();
include 'conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_aula = $_POST['id_aula'];
    $concluido = $_POST['concluido'] === 'true';

    if ($concluido) {
        // Marcar a aula como concluída
        $stmt = $conn->prepare("INSERT INTO aulas_concluidas (id_usuario, id_aula) VALUES (?, ?) ON DUPLICATE KEY UPDATE id_aula = id_aula");
        $stmt->bind_param("ii", $id_usuario, $id_aula);
    } else {
        // Desmarcar a aula
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
}
?>