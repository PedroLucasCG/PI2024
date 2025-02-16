<?php
use PHPUnit\Framework\TestCase;
require __DIR__ . '/../services/acordo/AvaliacaoService.php';

class AvaliacaoTest extends TestCase
{
    private $pdo;
    private $stmt;
    private $avaliacaoService;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->avaliacaoService = new AvaliacaoService($this->pdo);
    }

    public function testUpsertInsertsNewAvaliacao()
    {
        $avaliacao = [
            'Acordo' => 1,
            'comentario' => 'Ótimo serviço!',
            'grau' => 5
        ];

        $this->stmt->method('execute')->willReturn(true);

        $result = $this->avaliacaoService->upsert($avaliacao['Acordo'], $avaliacao['comentario'], $avaliacao['grau']);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Avaliação criada com sucesso', $result['msg']);
    }

    public function testGetAvaliacaoReturnsData()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetch')->willReturn([
            'idAvaliacao' => $id,
            'comentario' => 'Ótimo serviço!'
        ]);

        $result = $this->avaliacaoService->get($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Avaliação recuperada com sucesso', $result['msg']);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals($id, $result['data']['idAvaliacao']);
    }

    public function testDeleteAvaliacaoReturnsSuccessMessage()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);

        $result = $this->avaliacaoService->delete($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Avaliação deletada com sucesso', $result['msg']);
    }
}
