<?php

require __DIR__ . '/../../services/acordo/AcordoService.php';
require __DIR__ . '/../../configs/databaseConfig.php';

$data = json_decode(file_get_contents('php://input'), true);

$valor = $data['valor'];
$descricao = $data['descricao'];
$estado = $data['estado'];
$modalidade = $data['modalidade'];
$contratante = $data['Contratante'];
$oferta = $data['Oferta'];

$service = new AcordoService($pdo);
$result = $service->upsert(id: null, valor: $valor, descricao: $descricao, estado: $estado, modalidade: $modalidade, Contratante: $contratante, Oferta: $oferta);
echo json_encode($result);
