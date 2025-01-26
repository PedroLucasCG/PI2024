<?php
require __DIR__ . '/../../services/acordo/AvaliacaoService.php';
require __DIR__ . '/../../configs/databaseConfig.php';

$data = json_decode(file_get_contents('php://input'), true);

$idAcordo = $data['idAcordo'];

$service = new AvaliacaoService($pdo);
$result = $service->get($idAcordo);
echo json_encode($result);
