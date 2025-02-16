<?php
use PHPUnit\Framework\TestCase;
require __DIR__ . '/../services/oferta/AreaService.php';
class AreaTest extends TestCase
{
    private $pdo;
    private $stmt;
    private $areaService;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->areaService = new AreaService($this->pdo);
    }

    public function testGetAllAreasReturnsData()
    {
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetchAll')->willReturn([
            ['idArea' => 1, 'nome' => 'Design'],
            ['idArea' => 2, 'nome' => 'Desenvolvimento']
        ]);

        $result = $this->areaService->getAll();

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Área recuperada com sucesso', $result['msg']);
        $this->assertArrayHasKey('data', $result);
        $this->assertCount(2, $result['data']);
    }

    public function testGetAllAreasReturnsErrorIfNoData()
    {
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetchAll')->willReturn([]);

        $result = $this->areaService->getAll();

        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('A área não foi encontrada.', $result['error']);
    }

    public function testDeleteAreaReturnsSuccessMessage()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);

        $result = $this->areaService->delete($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Área deletada com sucesso', $result['msg']);
    }

    public function testDeleteAreaReturnsErrorMessageWhenIdIsMissing()
    {
        $result = $this->areaService->delete(null);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('O id é necessário para deletar área.', $result['msg']);
    }
}
