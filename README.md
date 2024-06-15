# ToDoList 📝

## Descrição ✏️

<p>Este projeto foi desenvolvido como parte de um trabalho acadêmico na faculdade. A aplicação é uma lista de tarefas que permite aos usuários criar, editar e excluir tarefas, bem como marcar tarefas como concluídas. O desenvolvimento foi baseado no tutorial disponível no <a href = "https://youtu.be/7KWpDC12X7U?si=DE_iFl2mbbIJaB3A">YouTube</a>, mas com diversas funcionalidades adicionais implementadas para melhorar e personalizar a aplicação.</p>

## Funcionalidades 💻

<p>Além das funcionalidades básicas demonstradas no vídeo, como criar, editar e excluir tarefas, fizemos as seguintes adições e melhorias:</p>

1. **Tarefas em Dia, Atrasadas e Completas:** As tarefas agora são categorizadas em três etapas: Tarefas em Dia, Tarefas Atrasadas e Tarefas Completas, para facilitar a organização.
2. **Tarefas Atrasadas:** Implementamos uma funcionalidade que identifica e move automaticamente as tarefas atrasadas para uma seção separada.
3. **Mensagem de Atraso:** Quando uma tarefa está atrasada, ela exibe uma mensagem indicando o tempo de atraso.
4. **Mensagens de Conclusão com Atraso:** Quando uma tarefa atrasada é marcada como concluída, ela exibe uma mensagem indicando que foi entregue com atraso e o tempo de atraso.

## Configuração do Projeto ⚙️

Pré-requisitos

- PHP 7.x ou superior
- PostgreSQL 11.4 ou superior
- Servidor web (Apache, Nginx, etc.)

Instalação

1. Clone o repositório:

```bash
git clone https://github.com/laycsz/ToDoList.git
cd ToDoList
 ```

2. Configure o banco de dados MySQL:

```bash
CREATE DATABASE to_do_list;

USE to_do_list;

CREATE TABLE task (
    id SERIAL PRIMARY KEY,
    description VARCHAR(50) NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    due_date TIMESTAMP NOT NULL,
    completed_at TIMESTAMP NULL,
    delay_message VARCHAR(255) NULL
);

 ```

3. Configure a conexão com o banco de dados em database/conn.php:
```bash
<?php
$hostname = 'localhost';
$database = 'to_do_list';
$username = 'postgres';
$password = 'sua_senha';

try {
    $pdo = new PDO("pgsql:host=$hostname;dbname=$database", $username, $password);
    // Definindo o modo de erro do PDO para exceção
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
    exit;
}
?>
 ```
4. Inicie o servidor PHP:
```bash
php -S localhost:5000
 ```
> Nota: Estamos usando a porta 5000 em vez da porta padrão 8000 para evitar conflitos com outros serviços que possam estar rodando na porta 8000. Se você preferir, pode usar qualquer outra porta disponível.
5. Acesse a aplicação no seu navegador em http://localhost:5000.

## Desenvolvimento
Este projeto foi desenvolvido por @laycsz e @gstvgms como parte de um trabalho acadêmico. O código base foi adaptado e expandido a partir do tutorial fornecido pela @Larissakich, muito obrigada!

