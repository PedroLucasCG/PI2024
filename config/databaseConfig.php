<?php

$host = 'localhost';
$dbname = '1corre_manager';
$username = 'root';
$password = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,                   
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    return ["msg" => "Conexão com o banco de dados bem sucedida."];
} catch (PDOException $e) {
    return ["msg" => "Conexão com o banco de dados falhou: : " . $e->getMessage()];
}
?>