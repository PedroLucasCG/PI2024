<?php
require __DIR__ . '/../../services/acordo/AcordoService.php';
require __DIR__ . '/../../configs/databaseConfig.php';

$data = json_decode(file_get_contents('php://input'), true);

$idOferta = $data['idAcordo'];

$service = new AcordoService($pdo);
$result = $service->delete($idOferta);
echo json_encode($result);
