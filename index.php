<?php
include 'database/conn.php';

// Consulta para obter todas as tarefas
try {
    $sql = "SELECT id, description, due_date, completed, completed_at, delay_message FROM task";
    $stmt = $pdo->query($sql);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link rel="icon" href="./src/images/to-do-list.png">
    <link rel="stylesheet" href="src/styles/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="src/js/script.js"></script>
</head>
<body>
    <div id="to_do">
        <h1>Task Manager</h1>
        <form class="to-do-form" action="actions/create.php" method="POST">
            <input type="text" name="description" placeholder="Task description" required>
            <input type="datetime-local" name="due_date" required>
            <button type="submit" class="form-button">+</button>
        </form>
        <h2>Tarefas</h2>
        <div id="tasks">
            <?php foreach ($tasks as $task): ?>
                <?php if (!$task['completed'] && new DateTime($task['due_date']) > new DateTime()): ?>
                    <div class="task" data-due-date="<?= $task['due_date'] ?>">
                        <div class="task-header">
                            <input type="checkbox" class="progress" data-task-id="<?= $task['id'] ?>" <?= $task['completed'] ? 'checked' : '' ?>>
                            <span class="task-description"><?= htmlspecialchars($task['description']) ?></span>
                            <div class="task-actions">
                                <a href="actions/delete.php?id=<?= $task['id'] ?>" class="action-button delete-button">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>
                                <a href="#" class="action-button edit-button">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                            </div>
                        </div>
                        <span class="due-date">Prazo: <?= (new DateTime($task['due_date']))->format('d/m/Y H:i') ?></span>
                        <span class="overdue-time"></span> <!-- Este elemento deve estar presente -->
                        <form action="actions/update.php" method="POST" class="to-do-form edit-task hidden">
                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                            <input type="text" name="description" value="<?= htmlspecialchars($task['description']) ?>" required>
                            <input type="datetime-local" name="due_date" value="<?= (new DateTime($task['due_date']))->format('Y-m-d\TH:i') ?>" required>
                            <button type="submit" class="form-button">Salvar</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <h2>Tarefas Atrasadas</h2>
        <div id="overdue-tasks">
            <?php foreach ($tasks as $task): ?>
                <?php if (!$task['completed'] && new DateTime($task['due_date']) <= new DateTime()): ?>
                    <div class="task overdue" data-due-date="<?= $task['due_date'] ?>">
                        <div class="task-header">
                            <input type="checkbox" class="progress" data-task-id="<?= $task['id'] ?>" <?= $task['completed'] ? 'checked' : '' ?>>
                            <span class="task-description"><?= htmlspecialchars($task['description']) ?></span>
                            <div class="task-actions">
                                <a href="actions/delete.php?id=<?= $task['id'] ?>" class="action-button delete-button">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>
                                <a href="#" class="action-button edit-button">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                            </div>
                        </div>
                        <span class="due-date">Prazo: <?= (new DateTime($task['due_date']))->format('d/m/Y H:i') ?></span>
                        <span class="overdue-time"></span> <!-- Este elemento deve estar presente -->
                        <form action="actions/update.php" method="POST" class="to-do-form edit-task hidden">
                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                            <input type="text" name="description" value="<?= htmlspecialchars($task['description']) ?>" required>
                            <input type="datetime-local" name="due_date" value="<?= (new DateTime($task['due_date']))->format('Y-m-d\TH:i') ?>" required>
                            <button type="submit" class="form-button">Salvar</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <h2>Tarefas Completadas</h2>
        <div id="completed-tasks">
            <?php foreach ($tasks as $task): ?>
                <?php if ($task['completed']): ?>
                    <div class="task completed" data-due-date="<?= $task['due_date'] ?>">
                        <div class="task-header">
                            <input type="checkbox" class="progress" data-task-id="<?= $task['id'] ?>" checked disabled>
                            <span class="task-description"><?= htmlspecialchars($task['description']) ?></span>
                            <div class="task-actions">
                                <a href="actions/delete.php?id=<?= $task['id'] ?>" class="action-button delete-button">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>
                            </div>
                        </div>
                        <span class="completed-at">Completada em: <?= (new DateTime($task['completed_at']))->format('d/m/Y H:i') ?></span>
                        <span class="overdue-time"><?= htmlspecialchars($task['delay_message'] ?? '') ?></span> <!-- Mantém a mensagem de atraso -->
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        function checkOverdueTasks() {
            const now = new Date();
            console.log('Current date and time:', now);

            $('#tasks .task, #overdue-tasks .task').each(function () {
                const dueDateString = $(this).data('due-date');
                const dueDate = new Date(dueDateString);
                console.log('Task due date and time:', dueDate);

                const overdueElement = $(this).find('.overdue-time');
                if (dueDate <= now && !$(this).find('.progress').is(':checked')) {
                    $(this).addClass('overdue');
                    const diffMs = now - dueDate; // diferença em milissegundos
                    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24)); // diferença em dias
                    const diffHrs = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)); // diferença em horas
                    const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60)); // diferença em minutos

                    let overdueText = '';
                    if (diffDays > 0) {
                        overdueText = `Atrasada há ${diffDays} dia(s), ${diffHrs} hora(s) e ${diffMins} minuto(s)`;
                    } else if (diffHrs > 0) {
                        overdueText = `Atrasada há ${diffHrs} hora(s) e ${diffMins} minuto(s)`;
                    } else {
                        overdueText = `Atrasada há ${diffMins} minuto(s)`;
                    }

                    overdueElement.text(overdueText).show();
                    // Move the task to the overdue tasks section if it's not already there
                    if ($(this).parent().attr('id') !== 'overdue-tasks') {
                        $('#overdue-tasks').append($(this));
                    }
                } else {
                    $(this).removeClass('overdue');
                    overdueElement.hide();
                    // Move back to the normal task list if it's not overdue
                    if ($(this).parent().attr('id') === 'overdue-tasks') {
                        $('#tasks').append($(this));
                    }
                }
            });
        }

        // Função para mover tarefas concluídas para a aba de tarefas completas
        function moveCompletedTask(task) {
            const overdueElement = task.find('.overdue-time');
            const overdueText = overdueElement.text(); // Obtém o texto de atraso
            task.removeClass('overdue'); // Remove a classe overdue
            task.addClass('completed'); // Adiciona a classe completed
            if (overdueText) {
                const completedAt = new Date(); // Data e hora atual
                let deliveryText = overdueText.replace('Atrasada há', 'Entregue com atraso de');
                overdueElement.text(deliveryText).show(); // Atualiza o texto de atraso para texto de entrega
                task.find('.completed-at').text(`Completada em: ${completedAt.toLocaleString('pt-BR')}`);
            }
            $('#completed-tasks').append(task);
        }

        // Chama a função checkOverdueTasks ao carregar a página
        checkOverdueTasks();

        // Intervalo para verificar tarefas atrasadas a cada minuto
        setInterval(checkOverdueTasks, 60000);

        $('.progress').on('click', function () {
            if ($(this).is(':checked')) {
                $(this).addClass('done');
            } else {
                $(this).removeClass('done');
            }
        });

        $('.progress').on('change', function () {
            const id = $(this).data('task-id');
            const completed = $(this).is(':checked') ? 'true' : 'false';
            const task = $(this).closest('.task');

            $.ajax({
                url: 'actions/update-progress.php',
                method: 'POST',
                data: {
                    id: id,
                    completed: completed
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        if (completed === 'true') {
                            moveCompletedTask(task);
                        } else {
                            location.reload();
                        }
                    } else {
                        alert('Erro ao editar a tarefa');
                    }
                },
                error: function () {
                    alert('Ocorreu um erro ao editar a tarefa');
                }
            });
        });

        $('.edit-button').on('click', function () {
            var task = $(this).closest('.task');
            task.find('.task-content').hide();
            task.find('.edit-task').removeClass('hidden');
        });

        $('.edit-task').on('submit', function (e) {
            e.preventDefault();
            const taskId = $(this).find('input[name="id"]').val();
            const newDescription = $(this).find('input[name="description"]').val();
            const newDueDate = $(this).find('input[name="due_date"]').val();
            $.ajax({
                url: 'actions/update.php',
                method: 'POST',
                data: {
                    id: taskId,
                    description: newDescription,
                    due_date: newDueDate
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Erro ao atualizar a tarefa');
                    }
                },
                error: function () {
                    alert('Ocorreu um erro ao atualizar a tarefa');
                }
            });
        });

        $('.delete-button').on('click', function (e) {
            e.preventDefault();
            const confirmed = confirm('Tem certeza que deseja apagar esta tarefa?');
            if (confirmed) {
                window.location.href = $(this).attr('href');
            }
        });
    });
    </script>
</body>
</html>
