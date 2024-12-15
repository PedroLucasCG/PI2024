<?php
require __DIR__ . '../../models/usuario/endereco.php';
require __DIR__ . '../../models/usuario/telefone.php';

class PessoaService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function upsert(array $pessoa)
    {
        extract(array: $pessoa);
        extract($endereco);
        $endereco = new Endereco($this->pdo);
        $endereco->setEndereco(
            estado: $estado,
            cidade: $cidade,
            bairro: $bairro,
            cep: $cep
        );
        $endereco_id = $endereco->create();

        if ($id) {
            $query = "UPDATE pessoa SET
                nome = :nome,
                email = :email,
                data_nasc = :data_nasc,
                cpf = :cpf,
                rg = :rg,
                senha = :senha,
                usuario = :usuario,
                data_registro = :data_registro,
                endereco_id = :endereco_id
                WHERE idPessoa = :id";
        } else {
            $query = "INSERT INTO pessoa (nome, email, data_nasc, cpf, rg, senha, usuario, data_registro, Endereco)
                VALUES (:nome, :email, :data_nasc, :cpf, :rg, :senha, :usuario, :data_registro, :endereco_id)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':data_registro', $data_registro);
        $stmt->bindParam(':endereco_id', $endereco_id);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Pessoa atualizada com sucesso."];
            } else {
                foreach ($telefones as $key => $value) {
                    $telefone = new Telefone($this->pdo);
                    $telefone->setTelefone( telefone: $value, pessoa_id: $this->pdo->lastInsertId());
                    $telefone->create();
                }

                return ["msg" => "Pessoa criada com sucesso."];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id): array
    {
        //trazer os telefones e o endereço junto
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar pessoa."];
        }

        $query = "SELECT * FROM Pessoa WHERE idPessoa = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Pessoa recuperada com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "A pessoa não foi encontrada."];
        }
    }

    public function delete($id): array
    {
        //deletar o endereço, pessoa e telefone (nessa ordem)
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar pessoa."];
        }

        $query = "DELETE FROM Endereco WHERE idPessoa = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Pessoa deletada com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o pessoa"];
        }
    }
}
