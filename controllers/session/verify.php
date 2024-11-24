<?php
header('Content-Type: application/json');
session_start();

if (isset($_SESSION['login'])) {
    echo json_encode($_SESSION['login']);
} else {
    echo json_encode(['error' => 'Sessão não existe']);
}
