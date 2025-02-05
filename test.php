<?php
require __DIR__ . '/configs/databaseConfig.php';
require __DIR__ . '/services/oferta/OfertaService.php';
require __DIR__ . '/services/pessoa/EnderecoService.php';

session_start();
print_r($_SESSION);
