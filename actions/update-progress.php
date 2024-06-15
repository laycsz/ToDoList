<?php
include '../database/conn.php';

$id = $_POST['id'];
$completed = $_POST['completed'];
$delay_message = null;

if ($completed === 'true') {
    $stmt = $pdo->prepare("SELECT due_date FROM task WHERE id = ?");
    $stmt->execute([$id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    $due_date = new DateTime($task['due_date']);
    $now = new DateTime();
    if ($now > $due_date) {
        $diff = $now->diff($due_date);
        $days = $diff->days;
        $hours = $diff->h;
        $minutes = $diff->i;
        $delay_message = "Entregue com atraso de $days dia(s), $hours hora(s) e $minutes minuto(s)";
    }
}

try {
    $sql = "UPDATE task SET completed = ?, completed_at = NOW(), delay_message = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$completed === 'true' ? 1 : 0, $delay_message, $id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
