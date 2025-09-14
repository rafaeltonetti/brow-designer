<?php
session_start();
include 'conexao.php';

header('Content-Type: application/json');

$response = array('success' => false, 'message' => '');

if (!isset($_SESSION['id_usuario'])) {
    $response['message'] = "Usuário não logado.";
    echo json_encode($response);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aula_id = isset($_POST['aula_id']) ? intval($_POST['aula_id']) : 0;
    $concluido = isset($_POST['concluido']) ? intval($_POST['concluido']) : 0;
    $id_usuario = $_SESSION['id_usuario'];

    if ($aula_id > 0) {
        if ($concluido == 1) {
            // Insere na tabela 'aulas_concluidas'
            $stmt = $conn->prepare("INSERT INTO aulas_concluidas (aula_id, usuario_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $aula_id, $id_usuario);
        } else {
            // Remove da tabela 'aulas_concluidas'
            $stmt = $conn->prepare("DELETE FROM aulas_concluidas WHERE aula_id = ? AND usuario_id = ?");
            $stmt->bind_param("ii", $aula_id, $id_usuario);
        }

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Status da aula atualizado com sucesso.";
        } else {
            $response['message'] = "Erro ao atualizar o status da aula: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = "ID da aula inválido.";
    }
} else {
    $response['message'] = "Requisição inválida.";
}

$conn->close();
echo json_encode($response);
?>