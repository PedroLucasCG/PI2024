<?php
require __DIR__ . '/../../services/acordo/AcordoService.php';
require __DIR__ . '/../../configs/databaseConfig.php';

$data = json_decode(file_get_contents('php://input'), true);

$idAcordo = $data['idAcordo'];
$estado = $data['estado'];

$service = new AcordoService($pdo);
$result = $service->setEstado($idAcordo, $estado);
echo json_encode($result);
