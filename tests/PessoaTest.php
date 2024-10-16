<?php
include __DIR__ . '/../services/pessoa/pessoa.php';
use PHPUnit\Framework\TestCase;

class PessoaTest extends TestCase
{
    private $pdo;
    private $pessoa;

    protected function setUp(): void
    {
        // Create a mock for the PDO class.
        $this->pdo = $this->createMock(PDO::class);
        
        // Create an instance of the Pessoa class, passing the mock PDO.
        $this->pessoa = new Pessoa($this->pdo);
        print_r($this->pessoa);
    }

    public function testUpsertCreatesNewPessoa()
    {
        // Create a mock statement for prepared statements
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);

        // Mock the PDO's prepare method to return the mock statement
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        // Test the upsert method (creating a new person)
        $result = $this->pessoa->upsert(
            'John Doe', '1990-01-01', '12345678900', '12345678',
            'password123', 'johndoe', 'john@example.com', '2024-09-30', 1
        );

        // Check that the returned message is correct
        $this->assertEquals('Pessoa criada com sucesso', $result['msg']);
    }

    public function testUpsertUpdatesExistingPessoa()
    {
        // Create a mock statement
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);

        // Mock the PDO's prepare method
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        // Test the upsert method (updating an existing person)
        $result = $this->pessoa->upsert(
            'Jane Doe', '1985-05-05', '98765432100', '87654321',
            'password456', 'janedoe', 'jane@example.com', '2024-09-30', 1, 123
        );

        // Check that the returned message is correct
        $this->assertEquals('Pessoa atualizada com sucesso.', $result['msg']);
    }

    public function testGetPessoaById()
    {
        // Mock the statement and the fetch method
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);
        $statement->method('fetch')->willReturn([
            'idPessoa' => 123,
            'nome' => 'John Doe',
        ]);

        // Mock the PDO prepare method
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        // Test the get method
        $result = $this->pessoa->get(123);

        // Verify the result
        $this->assertEquals('Pessoa recuperada com sucesso', $result['msg']);
        $this->assertEquals('John Doe', $result['data']['nome']);
    }

    public function testDeletePessoa()
    {
        // Create a mock statement
        $statement = $this->createMock(PDOStatement::class);
        $statement->method('execute')->willReturn(true);

        // Mock the PDO prepare method
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($statement);

        // Test the delete method
        $result = $this->pessoa->delete(123);

        // Verify the result
        $this->assertEquals('Pessoa deletada com sucesso', $result['msg']);
    }

    public function testUpsertReturnsErrorWhenEnderecoNotFound()
    {
        // Mock the Endereco object to simulate a missing address
        $enderecoMock = $this->createMock(Endereco::class);
        $enderecoMock->method('get')->willReturn(['data' => null]);

        // Inject the Endereco mock into the Pessoa object
        $this->pessoa = new Pessoa($this->pdo);
        
        // Test the upsert method with a non-existing address
        $result = $this->pessoa->upsert(
            'John Doe', '1990-01-01', '12345678900', '12345678',
            'password123', 'johndoe', 'john@example.com', '2024-09-30', 999 // Invalid endereco_id
        );

        // Verify the result
        $this->assertEquals('Endereço não consta no sistema.', $result['msg']);
    }
}
