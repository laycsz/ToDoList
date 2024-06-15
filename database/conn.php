<?php
$hostname = 'localhost';
$database = 'to_do_list';
$username = 'postgres';
$password = '0511';

try {
    $pdo = new PDO("pgsql:host=$hostname;dbname=$database", $username, $password);
    // Definindo o modo de erro do PDO para exceção
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
    exit;
}
?>
