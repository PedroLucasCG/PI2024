<?php

class Pessoa {
    private $nome;
    private $data_nasc;
    private $cpf;
    private $senha;
    private $email;
    private $endereco;
    private $telefones;

    public function  __construct($nome, $data_nasc, $cpf, $senha, $email, $endereco, $telefones) {
        $this->nome = $nome;
        $this->data_nasc = $data_nasc;
        $this->cpf = $cpf;
        $this->senha = $senha;
        $this->email = $email;
        $this->endereco = $endereco;
        $this->telefones = $telefones;
    }

    public function getAllAttributes() {
        return [
            "nome" => $nome,
            "data_nasc" => $data_nasc,
            "cpf" => $cpf,
            "senha" => $senha,
            "email" => $email,
            "endereco" => $endereco,
            "telefones" => $telefones
        ];
    }
}