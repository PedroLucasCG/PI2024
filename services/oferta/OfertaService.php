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

    public function get($idPessoa)
    {
        if (!isset($idPessoa)) {
            return ["msg" => "O id é necessário para recuperar oferta."];
        }

        $query = "SELECT * FROM Oferta WHERE Freelancer = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $idPessoa);

        $stmt->execute();
        $data = $stmt->fetchAll();

        if ($data) {
            return [
                "msg" => "Oferta recuperada com sucesso",
                "data" => $data,
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

        $query = "DELETE FROM Oferta WHERE idOferta = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Oferta deletada com sucesso."];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar a oferta."];
        }
    }
}
