<?php
class PessoaService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function upsert(array $pessoa): array|null
    {
        extract(array: $pessoa);
        extract($endereco);
        $endereco = new Endereco($this->pdo);
        $endereco->setEndereco(
            estado: $estado,
            cidade: $cidade,
            bairro: $bairro,
        );
        $endereco_id = $endereco->create();

        if ($id) {
            $query = "UPDATE pessoa SET
                email = :email,
                data_nasc = :data_nasc,
                senha = :senha,
                usuario = :usuario,
                data_registro = :data_registro,
                Endereco = :endereco_id
                WHERE idPessoa = :id";
        } else {
            $query = "INSERT INTO pessoa (nome, email, data_nasc, cpf, senha, usuario, data_registro, Endereco)
                VALUES (:nome, :email, :data_nasc, :cpf, :senha, :usuario, :data_registro, :endereco_id)";
        }

        $stmt = $this->pdo->prepare($query);
        $current_date = date('Y-m-d');

        if (!isset($id)) {
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':cpf', $cpf);
        }
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':data_registro', $current_date);
        $stmt->bindParam(':endereco_id', $endereco_id);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                print_r($_FILES);
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_FILES['profileImg']) && $_FILES['profileImg']['error'] === UPLOAD_ERR_OK) {
                        $fileTmpPath = $_FILES['profileImg']['tmp_name'];
                        $fileName = $_FILES['profileImg']['name'];
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

                        session_start();
                        $uploadDir = __DIR__ . '/../../uploads/' . $_SESSION['login']['idPessoa'] . '/';

                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        $fileNameNew = substr(md5(microtime()),rand(0,26),5) . $_SESSION['login']['idPessoa'] . '.' . $fileExtension;
                        $destPath = $uploadDir . $fileNameNew;

                        $query = "UPDATE pessoa SET foto = '$fileNameNew' WHERE idPessoa = " . $_SESSION['login']['idPessoa'] ;
                        $stmt = $this->pdo->prepare($query);
                        try {
                            $stmt->execute();
                        } catch (PDOException $ex) {
                            echo "ALgo deu errado a imagem da oferta" . $ex->getMessage();
                        }

                        if (move_uploaded_file($fileTmpPath, $destPath)) {
                            echo "Arquivo movido com sucesso";
                        } else {
                            echo "Erro na movimentação do arquivo";
                        }
                    } else {
                        echo "Nenhum arquivo enviado ou houve algum erro";
                    }
                } else {
                    echo "Solicitação inválida";
                }
                return ["msg" => "Pessoa atualizada com sucesso."];
            } else {
                foreach ($telefones as $key => $value) {
                    $telefone = new Telefone($this->pdo);
                    $err = $telefone->setTelefone( telefone: $value, pessoa_id: $this->pdo->lastInsertId());
                    if (isset($err['error']))
                        return $err;
                    $telefone->create();
                }

                return ["msg" => "Pessoa criada com sucesso."];
            }
        } else {
            return ["error" => "Erro na execução da query."];
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
