<?php
require __DIR__ . '/services/oferta/OfertaService.php';
require __DIR__ . '/configs/databaseConfig.php';

$service = new OfertaService($pdo);
$result = $service->get(35);
echo json_encode($result);
