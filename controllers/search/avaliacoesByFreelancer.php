<?php

require __DIR__ . '/../../services/acordo/AvaliacaoService.php';
require __DIR__ . '/../../configs/databaseConfig.php';

$data = json_decode(file_get_contents('php://input'), true);

$idPessoa = $data['idPessoa'];

$service = new AvaliacaoService($pdo);
$result = $service->getByFreelancer($idPessoa);
echo json_encode($result);
