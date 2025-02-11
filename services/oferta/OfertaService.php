<?php

class OfertaService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function upsert($oferta): array
{
        extract($oferta);
        if ($id) {
            $query = "UPDATE Oferta SET
                Freelancer = :Freelancer,
                Area = :Area,
                preco = :preco,
                descricao = :descricao
                titulo = :titulo
            WHERE idOferta = :id";
        } else {
            $query = "INSERT INTO Oferta (Freelancer, Area, preco, descricao, titulo)
                VALUES (:Freelancer, :Area, :preco, :descricao, :titulo)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':Freelancer', $Freelancer);
        $stmt->bindParam(':Area', $Area);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':titulo', $titulo);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            $insertionId = isset($id) ? $id : $this->pdo->lastInsertId();
            $query = "insert into periodo (Oferta, dia, hora_inicio, hora_final) values (:Oferta, :dia, :hora_inicio, :hora_final)";
            foreach ($periodos as $key => $value) {
                $diaHora = explode(',', $value);
                $horas = explode('-', trim($diaHora[1]));
                $dia = trim($diaHora[0]);
                $hora_inicio = trim($horas[0]);
                $hora_final = trim($horas[1]);

                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':Oferta', $insertionId);
                $stmt->bindParam(':dia', $dia);
                $stmt->bindParam(':hora_inicio', $hora_inicio);
                $stmt->bindParam(':hora_final', $hora_final);

                try {
                    $stmt->execute();
                } catch (PDOException $ex) {
                    echo "ALgo deu errado ao salvar os períodos da sua oferta!" . $ex->getMessage();
                }
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($file['amostraFoto']) && $file['amostraFoto']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $file['amostraFoto']['tmp_name'];
                    $fileName = $file['amostraFoto']['name'];
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

                    session_start();
                    $uploadDir = __DIR__ . '/../../uploads/' . $_SESSION['login']['idPessoa'] . '/';

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileNameNew = $insertionId . '.' . $fileExtension;
                    $destPath = $uploadDir . $fileNameNew;

                    $query = "UPDATE oferta SET foto = '$fileNameNew' WHERE idOferta = $insertionId";
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

            if ($id) {
                return ["msg" => "Oferta atualizada com sucesso."];
            } else {
                return ["msg" => "Oferta criada com sucesso"];
            }
        } else {
            return ["error" => "Erro na execução da query."];
        }
    }

    public function get($idOferta)
    {
        if (empty($idOferta)) {
            return ["msg" => "O id é necessário para recuperar oferta."];
        }

        $query = "SELECT
        `pessoa`.`idPessoa`,
        `pessoa`.`nome` AS nomePessoa,
        `pessoa`.`data_nasc`,
        `pessoa`.`cpf`,
        `pessoa`.`senha`,
        `pessoa`.`usuario`,
        `pessoa`.`email`,
        `pessoa`.`Endereco`,
        `pessoa`.`data_registro`,
        `pessoa`.`foto` AS pessoaFoto,
        `endereco`.`idEndereco`,
        `endereco`.`cep`,
        `endereco`.`estado`,
        `endereco`.`cidade`,
        `endereco`.`bairro`,
        `oferta`.`idOferta`,
        `oferta`.`descricao`,
        `oferta`.`preco`,
        `oferta`.`Freelancer`,
        `oferta`.`Area`,
        `oferta`.`titulo`,
        `oferta`.`foto` AS ofertaFoto,
        `telefone`.`idTelefone`,
        `telefone`.`telefone`,
        `telefone`.`Pessoa`,
        `area`.`idArea`,
        `area`.`nome`
        FROM oferta
        JOIN pessoa ON Freelancer = idPessoa
        JOIN endereco ON Endereco = idEndereco
        JOIN telefone ON Pessoa = idPessoa
        JOIN area ON Area = idArea
        WHERE idOferta = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $idOferta, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($data as $index => $row) {
            $id = $row['idOferta'];
            $queryPeriodos = "SELECT * FROM periodo WHERE Oferta = :id";
            $stmtPeriodos = $this->pdo->prepare($queryPeriodos);
            $stmtPeriodos->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtPeriodos->execute();

            $periodos = $stmtPeriodos->fetchAll(PDO::FETCH_ASSOC);

            $result[$index] = array_merge($row, ['periodos' => $periodos]);
        }

        if ($data) {
            return [
                "msg" => "Oferta recuperada com sucesso",
                "data" => $result[0],
            ];
        } else {
            return ["msg" => "A oferta não foi encontrada."];
        }
    }

    public function getByFreelancer($idPessoa) {
        if (empty($idPessoa)) {
            return ["msg" => "O id é necessário para recuperar oferta."];
        }

        $query = "SELECT
        *
        FROM pessoa
        JOIN oferta ON Freelancer = idPessoa
        WHERE idPessoa = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $idPessoa, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($data as $index => $row) {
            $id = $row['idOferta'];
            $queryPeriodos = "SELECT * FROM periodo WHERE Oferta = :id";
            $stmtPeriodos = $this->pdo->prepare($queryPeriodos);
            $stmtPeriodos->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtPeriodos->execute();

            $periodos = $stmtPeriodos->fetchAll(PDO::FETCH_ASSOC);

            $result[$index] = array_merge($row, ['periodos' => $periodos]);
        }

        if ($data) {
            return [
                "msg" => "Oferta recuperada com sucesso",
                "data" => $result,
            ];
        } else {
            return ["msg" => "A oferta não foi encontrada."];
        }
    }
    public function getAll(?string $search = '', ?string $cidade, ?int $page = 0, ?int $size = 30): array
    {
        $offset = $size * $page;
        $query = "SELECT * FROM pessoa
        JOIN oferta ON Freelancer = idPessoa
        JOIN endereco ON idEndereco = Endereco
        WHERE (oferta.titulo LIKE :search1 OR oferta.descricao LIKE :search2)";

        $countQuery = "SELECT COUNT(*) as total FROM oferta
        JOIN pessoa ON Freelancer = idPessoa
        JOIN endereco ON idEndereco = Endereco
        WHERE (oferta.titulo LIKE :search1 OR oferta.descricao LIKE :search2)";

        if($cidade) {
            $query .= " AND endereco.cidade = :cidade ";
            $countQuery .= " AND endereco.cidade = :cidade ";
        }

        $query .= " LIMIT :size OFFSET :offset ";

        $stmt = $this->pdo->prepare($query);

        if($cidade) {
            $stmt->bindParam(':cidade', $cidade, PDO::PARAM_STR);
        }
        $stmt->bindParam(':size', $size, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        $searchParam = "%" . $search . "%";
        $stmt->bindParam(':search1', $searchParam, PDO::PARAM_STR);
        $stmt->bindParam(':search2', $searchParam, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($data as $index => $row) {
            $id = $row['idOferta'];
            $queryPeriodos = "SELECT * FROM periodo WHERE Oferta = :id";
            $stmtPeriodos = $this->pdo->prepare($queryPeriodos);
            $stmtPeriodos->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtPeriodos->execute();

            $periodos = $stmtPeriodos->fetchAll(PDO::FETCH_ASSOC);

            $result[$index] = array_merge($row, ['periodos' => $periodos]);
        }

        if ($data) {
            $stmt = $this->pdo->prepare($countQuery);
            if($cidade) {
                $stmt->bindParam(':cidade', $cidade, PDO::PARAM_STR);
            }

            $searchParam = "%" . $search . "%";
            $stmt->bindParam(':search1', $searchParam, PDO::PARAM_STR);
            $stmt->bindParam(':search2', $searchParam, PDO::PARAM_STR);
            $stmt->execute();
            $total = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                "msg" => "Oferta recuperada com sucesso",
                "data" => $result,
                "totalPages" => ceil($total['total']/$size),
            ];
        } else {
            return ["msg" => "A oferta não foi encontrada."];
        }
    }

    public function delete($id)
    {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar oferta."];
        }

        $query_periodo = "DELETE FROM periodo WHERE Oferta = :id";
        $query = "DELETE FROM oferta WHERE idOferta = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);

        $stmt_periodo = $this->pdo->prepare($query_periodo);
        $stmt_periodo->bindParam(':id', $id);

        $stmt_periodo->execute();

        if ($stmt->execute()) {
            return ["msg" => "Oferta deletada com sucesso."];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar a oferta."];
        }
    }
}
