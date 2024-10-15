<?php
include __DIR__ . '/config/databaseConfig.php';
include __DIR__ . '/services/oferta/oferta.php';

class Amostra {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $nome, $descricao, $url, $imagem, $Oferta) {

        $oferta = new Oferta($this->pdo);
        if(!isset($oferta->get($Oferta)["data"])) {
            return ["msg" => "Oferta não consta no sistema."];
        }

        if ($id) {
            $query = "UPDATE Amostra SET 
                nome = :nome, 
                descricao = :descricao,
                url = :url,
                imagem: = :imagem,
                Oferta = :Oferta
            WHERE idAmostra = :id";
        } else {
            $query = "INSERT INTO Amostra (nome, descricao, url, imagem, Oferta)
                VALUES (:nome, :descricao, :url, :imagem, :Oferta)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descicao', $descricao);
        $stmt->bindParam(':url', $url);
        $stmt->bindParam(':imagem', $imagem);
        $stmt->bindParam(':Oferta', $Oferta);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Amostra atualizada com sucesso."];
            } else {
                return ["msg" => "Amostra criada com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar área."];
        }

        $query = "SELECT * FROM Amostra WHERE idAmostra = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Amostra recuperada com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "A amostra não foi encontrada."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar amostra."];
        }

        $query = "DELETE FROM Amostra WHERE idAmostra = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Amostra deletada com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar a amostra."];
        }
    }
}

$amostra = new Amostra($pdo);

$amostra->upsert('Limpeza de carros', 'Limpeza completa', 'google.com', 'bg.png', 1);

$amostra->upsert(1, 'Limpeza de carros', 'Limpeza completa', 'google.com', 'bg.png', 1);

$amostra->get(1);


