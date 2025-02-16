<?php
use PHPUnit\Framework\TestCase;
require __DIR__ . '/../services/oferta/OfertaService.php';
class OfertaTest extends TestCase
{
    private $pdo;
    private $stmt;
    private $ofertaService;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->ofertaService = new OfertaService($this->pdo);
    }

    public function testUpsertInsertsNewOferta()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $oferta = [
            'Freelancer' => 1,
            'Area' => 'Design',
            'preco' => 150.00,
            'descricao' => 'Serviço de design gráfico',
            'titulo' => 'Design Profissional',
            'periodos' => ['segunda, 14:00 - 15:00']
        ];

        $this->stmt->method('execute')->willReturn(true);
        $this->pdo->method('lastInsertId')->willReturn(1);

        $result = $this->ofertaService->upsert($oferta);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Oferta criada com sucesso', $result['msg']);
    }

    public function testUpsertUpdatesExistingOferta()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $oferta = [
            'id' => 1,
            'Freelancer' => 1,
            'Area' => 'Desenvolvimento Web',
            'preco' => 300.00,
            'descricao' => 'Criação de sites',
            'titulo' => 'Web Development',
            'periodos' => ['segunda, 14:00 - 15:00']
        ];

        $this->stmt->method('execute')->willReturn(true);

        $result = $this->ofertaService->upsert($oferta);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Oferta atualizada com sucesso.', $result['msg']);
    }

    public function testGetOfertaReturnsData()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetchAll')->willReturn([
            [
                'idOferta' => $id,
                'descricao' => 'Serviço de design gráfico'
            ]
        ]);

        $result = $this->ofertaService->get($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Oferta recuperada com sucesso', $result['msg']);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals($id, $result['data']['idOferta']);
    }

    public function testDeleteOfertaReturnsSuccessMessage()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);

        $result = $this->ofertaService->delete($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Oferta deletada com sucesso.', $result['msg']);
    }
}
