<?php
require_once('../database/conn.php');

$description = filter_input(INPUT_POST, 'description');
$due_date = filter_input(INPUT_POST, 'due_date');

if ($description && $due_date) {
    $sql = $pdo->prepare("INSERT INTO task (description, due_date) VALUES (:description, :due_date)");
    $sql->bindValue(':description', $description);
    $sql->bindValue(':due_date', $due_date);
    $sql->execute();

    header('Location: ../index.php');
    exit;
} else {
    header('Location: ../index.php');
    exit;
}
?>
