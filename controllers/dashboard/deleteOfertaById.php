<?php
require __DIR__ . '/../../services/oferta/OfertaService.php';
require __DIR__ . '/../../configs/databaseConfig.php';

$data = json_decode(file_get_contents('php://input'), true);

$idOferta = $data['idOferta'];

$service = new OfertaService($pdo);
$result = $service->delete($idOferta);
echo json_encode($result);
