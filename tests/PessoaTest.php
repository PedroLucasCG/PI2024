<?php
include __DIR__ . '/../services/pessoa/pessoa.php';
use PHPUnit\Framework\TestCase;

class PessoaTest extends TestCase
{
    private $pdo;
    private $pessoa;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        
        $this->pessoa = new Pessoa($this->pdo);
        print_r($this->pessoa);
    }

    public function testUpsertCreatesNewPessoa()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        $result = $this->pessoa->upsert(
            'John Doe', '1990-01-01', '12345678900', '12345678',
            'password123', 'johndoe', 'john@example.com', '2024-09-30', 1
        );

        $this->assertEquals('Pessoa criada com sucesso', $result['msg']);
    }

    public function testUpsertUpdatesExistingPessoa()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        $result = $this->pessoa->upsert(
            'Jane Doe', '1985-05-05', '98765432100', '87654321',
            'password456', 'janedoe', 'jane@example.com', '2024-09-30', 1, 123
        );

        $this->assertEquals('Pessoa atualizada com sucesso.', $result['msg']);
    }

    public function testGetPessoaById()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn([
            'idPessoa' => 123,
            'nome' => 'John Doe',
        ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        $result = $this->pessoa->get(123);

        $this->assertEquals('Pessoa recuperada com sucesso', $result['msg']);
        $this->assertEquals('John Doe', $result['data']['nome']);
    }

    public function testDeletePessoa()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        $result = $this->pessoa->delete(123);

        $this->assertEquals('Pessoa deletada com sucesso', $result['msg']);
    }

    public function testUpsertReturnsErrorWhenEnderecoNotFound()
    {
        $enderecoMock = $this->createMock(Endereco::class);
        $enderecoMock->method('get')->willReturn(['data' => null]);

        $this->pessoa = new Pessoa($this->pdo);
        
        $result = $this->pessoa->upsert(
            'John Doe', '1990-01-01', '12345678900', '12345678',
            'password123', 'johndoe', 'john@example.com', '2024-09-30', 999
        );

        $this->assertEquals('Endereço não consta no sistema.', $result['msg']);
    }
}
