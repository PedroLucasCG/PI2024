<?php
require __DIR__ . '../configs/databaseConfig.php';
require __DIR__ . './telefone.php';
require __DIR__ . './endereco.php';

class Pessoa
{
    private $id;
    private $nome;
    private $data_nasc;
    private $cpf;
    private $senha;
    private $email;
    private $endereco;
    private $telefones = array();

    public function __construct($nome, $data_nasc, $cpf, $senha, $email, $telefones, $estado, $cidade, $bairro, $cep = null, $id = null)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->data_nasc = $data_nasc;
        $this->cpf = $cpf;
        $this->senha = $senha;
        $this->email = $email;
        $this->endereco = new Endereco($estado, $cidade, $bairro, $cep);
        foreach ($telefones as $key => $value) {
            array_push($telefones, new Telefone($value));
        }
    }

    public function getAllAttributes()
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'data_nasc' => $this->data_nasc,
            'cpf' => $this->cpf,
            'senha' => $this->senha,
            'email' => $this->email,
            'endereco' => $this->endereco,
            'telefones' => $this->telefones
        ];
    }
}
