<?php
use PHPUnit\Framework\TestCase;
require __DIR__ . '/../services/acordo/AcordoService.php';

class AcordoTest extends TestCase
{
    private $pdo;
    private $stmt;
    private $acordoService;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->acordoService = new AcordoService($this->pdo);
    }

    public function testUpsertInsertsNewAcordo()
    {
        $acordo = [
            'valor' => 500.00,
            'descricao' => 'Contrato de design',
            'estado' => 'ativo',
            'modalidade' => 'remoto',
            'Contratante' => 1,
            'Oferta' => 2
        ];

        $this->stmt->method('execute')->willReturn(true);

        $result = $this->acordoService->upsert(null, ...array_values($acordo));

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Acordo criado com sucesso', $result['msg']);
    }

    public function testGetAcordoReturnsData()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetch')->willReturn([
            'idAcordo' => $id,
            'idOferta' => 1,
            'valor' => 500.00
        ]);

        $result = $this->acordoService->get($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Acordo recuperado com sucesso', $result['msg']);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals($id, $result['data']['idAcordo']);
    }

    public function testDeleteAcordoReturnsSuccessMessage()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);

        $result = $this->acordoService->delete($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Acordo deletado com sucesso', $result['msg']);
    }
}
