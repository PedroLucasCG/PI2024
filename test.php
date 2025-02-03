<?php
require __DIR__ . '/services/acordo/AcordoService.php';
require __DIR__ . '/configs/databaseConfig.php';

$service = new AcordoService($pdo);
$result = $service->upsert(null, "333", 'ofertona', 'proposto', 'horista', "10", "36");
echo '<pre>';
echo json_encode($result);
echo '</pre>';
