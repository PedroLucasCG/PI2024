<?php
use PHPUnit\Framework\TestCase;

class EnderecoTest extends TestCase
{
    private $pdo;
    private $stmt;
    private $enderecoService;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->enderecoService = new EnderecoService($this->pdo);
    }

    public function testUpsertInsertsNewEndereco()
    {
        $endereco = [
            'cep' => '12345-678',
            'estado' => 'SP',
            'cidade' => 'São Paulo',
            'bairro' => 'Centro'
        ];

        $this->stmt->method('execute')->willReturn(true);
        $this->pdo->method('lastInsertId')->willReturn(1);

        $result = $this->enderecoService->upsert($endereco);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Endereço criado com sucesso', $result['msg']);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals(1, $result['id']);
    }

    public function testUpsertUpdatesExistingEndereco()
    {
        $endereco = [
            'id' => 1,
            'cep' => '98765-432',
            'estado' => 'RJ',
            'cidade' => 'Rio de Janeiro',
            'bairro' => 'Copacabana'
        ];

        $this->stmt->method('execute')->willReturn(true);

        $result = $this->enderecoService->upsert($endereco);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Endereço atualizado com sucesso.', $result['msg']);
    }

    public function testGetEnderecoReturnsData()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetch')->willReturn([
            'idEndereco' => $id,
            'cep' => '12345-678'
        ]);

        $result = $this->enderecoService->get($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Endereço recuperado com sucesso', $result['msg']);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals($id, $result['data']['idEndereco']);
    }

    public function testDeleteEnderecoReturnsSuccessMessage()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);

        $result = $this->enderecoService->delete($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Endereço deletado com sucesso', $result['msg']);
    }
}
