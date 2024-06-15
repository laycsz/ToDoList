<?php

require_once('../database/conn.php');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$description = filter_input(INPUT_POST, 'description');
$due_date = filter_input(INPUT_POST, 'due_date');

// Verificação adicional para depuração
if (!$id) {
    file_put_contents('php://stderr', "ID não recebido\n");
}
if (!$description) {
    file_put_contents('php://stderr', "Descrição não recebida\n");
}
if (!$due_date) {
    file_put_contents('php://stderr', "Data de vencimento não recebida\n");
}

file_put_contents('php://stderr', print_r("ID: $id\n", true));
file_put_contents('php://stderr', print_r("Description: $description\n", true));
file_put_contents('php://stderr', print_r("Due Date: $due_date\n", true));

if ($id && $description && $due_date) {
    try {
        $sql = $pdo->prepare("UPDATE task SET description = :description, due_date = :due_date WHERE id = :id");
        $sql->bindValue(':description', $description);
        $sql->bindValue(':due_date', $due_date);
        $sql->bindValue(':id', $id);
        $sql->execute();

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Dados incompletos.']);
    exit;
}

?>
