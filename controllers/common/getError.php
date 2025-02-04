<?php
header('Content-Type: application/json');
session_start();

if (isset($_SESSION['error'])) {
    echo json_encode($_SESSION['error']);
    unset($_SESSION['error']);
} else {
    echo json_encode(['error' => 'Não há erro.']);
}
