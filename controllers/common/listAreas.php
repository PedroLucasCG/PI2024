<?php

require __DIR__ . '/../../services/oferta/AreaService.php';
require __DIR__ . '/../../configs/databaseConfig.php';

$service = new AreaService($pdo);
$result = $service->getAll();
echo json_encode($result);
