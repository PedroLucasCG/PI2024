<?php
require __DIR__ . '/configs/databaseConfig.php';
require __DIR__ . '/services/oferta/OfertaService.php';
require __DIR__ . '/services/pessoa/EnderecoService.php';

$search = $data['search'] ?? '';
$page = $data['page'] ?? 0;
$size = $data['size'] ?? 1;

session_start();
$enderecoService = new EnderecoService($pdo);
$service = new OfertaService($pdo);
if (isset($_SESSION['login']['Endereco'])) {
    $endereco = $enderecoService->get($_SESSION['login']['Endereco']);
}
$result = $service->getAll($search, $endereco['data']['cidade'] ?? null, $page, $size);
echo json_encode($result);
