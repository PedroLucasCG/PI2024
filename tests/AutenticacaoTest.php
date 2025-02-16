<?php
use PHPUnit\Framework\TestCase;
require __DIR__ . '/../services/pessoa/autenticacao.php';
class AutenticacaoTest extends TestCase
{
    private $pdo;
    private $stmt;
    private $autenticacao;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->autenticacao = new Autenticacao($this->pdo);
    }

    public function testLoginSuccessful()
    {
        $email = 'user@example.com';
        $senha = 'securepassword';

        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetch')->willReturn([
            'idPessoa' => 1,
            'email' => $email
        ]);

        $result = $this->autenticacao->login($email, $senha);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Login realizado com sucesso.', $result['msg']);
        $this->assertArrayHasKey('userId', $result);
        $this->assertEquals(1, $result['userId']);
    }

    public function testLoginFailsWithInvalidCredentials()
    {
        $email = 'user@example.com';
        $senha = 'wrongpassword';

        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetch')->willReturn(false);

        $result = $this->autenticacao->login($email, $senha);

        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('O login do usuário não existe.', $result['error']);
    }

    public function testLoginHandlesPDOException()
    {
        $email = 'user@example.com';
        $senha = 'securepassword';

        $this->stmt->method('execute')->willThrowException(new PDOException('Database error'));

        $result = $this->autenticacao->login($email, $senha);

        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('Ocorreu um erro ao realizar o login do usuário.', $result['error']);
    }
}
