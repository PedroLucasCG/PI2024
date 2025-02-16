<?php
require __DIR__ . '/../../services/acordo/AvaliacaoService.php';
require __DIR__ . '/../../configs/databaseConfig.php';

$data = json_decode(file_get_contents('php://input'), true);

$idAcordo = $data['idAcordo'];
$grau = $data['grau'];
$comentario = $data['comentario'];

$service = new AvaliacaoService($pdo);
$result = $service->upsert($idAcordo, $comentario, $grau);
echo json_encode($result);
