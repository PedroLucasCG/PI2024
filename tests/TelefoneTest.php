<?php
use PHPUnit\Framework\TestCase;

class TelefoneTest extends TestCase
{
    private $pdo;
    private $stmt;
    private $telefoneService;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->telefoneService = new TelefoneService($this->pdo);
    }

    public function testUpsertInsertsNewTelefone()
    {
        $telefone = [
            'telefone' => '11987654321',
            'Pessoa' => 1
        ];

        $this->stmt->method('execute')->willReturn(true);

        $result = $this->telefoneService->upsert($telefone);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Telefone criado com sucesso', $result['msg']);
    }

    public function testUpsertUpdatesExistingTelefone()
    {
        $telefone = [
            'id' => 1,
            'telefone' => '11987654321',
            'Pessoa' => 1
        ];

        $this->stmt->method('execute')->willReturn(true);

        $result = $this->telefoneService->upsert($telefone);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Telefone atualizado com sucesso.', $result['msg']);
    }

    public function testGetTelefoneReturnsData()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetch')->willReturn([
            'idTelefone' => $id,
            'telefone' => '11987654321'
        ]);

        $result = $this->telefoneService->get($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Telefone recuperado com sucesso', $result['msg']);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals($id, $result['data']['idTelefone']);
    }

    public function testDeleteTelefoneReturnsSuccessMessage()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);

        $result = $this->telefoneService->delete($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Telefone deletado com sucesso', $result['msg']);
    }
}
