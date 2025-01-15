<?php
require __DIR__ . '/models/oferta/oferta.php';
require __DIR__ . '/services/oferta/OfertaService.php';
require __DIR__ . '/configs/databaseConfig.php';

$oferta = new Oferta($pdo);
$oferta->setOferta(descricao: 'oferta', preco: 255, Freelancer: 9, Area: 1, periodos: ['segunda, 14:00 - 15:00'], titulo: 'Ofertona');
$oferta->create();

//todos
/**
 * trazer os periodos das ofertas
 * criar os cards de oferta de maneira dinamica
 *      recuperar os periosdos em spans
 */
