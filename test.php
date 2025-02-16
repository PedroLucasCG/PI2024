<?php
require __DIR__ . '/configs/databaseConfig.php';
require __DIR__ . '/services/oferta/OfertaService.php';
require __DIR__ . '/services/pessoa/EnderecoService.php';

$idArea = 1;

$service = new OfertaService($pdo);
$result = $service->getByArea($idArea);
echo json_encode($result);
