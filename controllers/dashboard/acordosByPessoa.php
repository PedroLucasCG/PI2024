<?php
require __DIR__ . '/../../services/acordo/AcordoService.php';
require __DIR__ . '/../../configs/databaseConfig.php';

$data = json_decode(file_get_contents('php://input'), true);

$idContratante = $data['idContratante'];
$idFreelancer = $data['idFreelancer'];
$status = $data['estado'];

$service = new AcordoService($pdo);
$result = $service->getAll($idFreelancer, $idContratante, $status);
echo json_encode($result);
