<?php
require __DIR__ . '/services/acordo/AcordoService.php';
require __DIR__ . '/configs/databaseConfig.php';

$service = new AcordoService($pdo);
$result = $service->setEstado(3, "ativo");
echo json_encode($result);

//todos
/**
 * trazer os periodos das ofertas
 * criar os cards de oferta de maneira dinamica
 *      recuperar os periosdos em spans
 */
