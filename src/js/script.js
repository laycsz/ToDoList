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
