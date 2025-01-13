<?php
require __DIR__ . '/../../services/oferta/OfertaService.php';
require __DIR__ . '/../../configs/databaseConfig.php';

$data = json_decode(file_get_contents('php://input'), true);

$idPessoa = $data['idPessoa'];

$service = new OfertaService($pdo);
$result = $service->get($idPessoa);
echo json_encode($result);
