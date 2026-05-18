<?php
namespace Api\Models;
use InvalidArgumentException;
use \JsonSerializable;

class Categoria implements JsonSerializable
{
    private int $idCategoria;
    private string $nomeCategoria;
    private string $descricao;

    public function __construct()
    {

    }

    public function getIdCategoria(): ?int
    {
       return $this->idCategoria;
    }

    public function setIdCategoria(int $value): void
    {
        if(!is_int($value)){
            throw new InvalidArgumentException("idCategoria deve ser um número inteiro.");
        }

        if($value<=0){
            throw new InvalidArgumentException("idCargo deve ser maior que zero.");
        }

        $this->idCargo=$value;
    }

    public function getNomeCategoria(): ?string
    {
        return $this->nomeCategoria;
    }

    public function setNomeCategoria(string $value): void
    {
        $nome=trim($value);

        if ($nome==='') {
            throw new InvalidArgumentException("nomeCategoria não pode ser vazio.");
        }

        $len = mb_strlen($nome);

        if ($len<3) {
            throw new InvalidArgumentException("nomeCategoria deve ter pelo menos 3 caracteres.");
        }

        if ($len>64) {
            throw new InvalidArgumentException("nomeCategoria deve ter ao máximo 64 caracteres.");
        }

        $this->nomeCategoria=$nome;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $value): void
    {
        $nome=trim($value);

        if ($nome==='') {
            throw new InvalidArgumentException("descricao não pode ser vazio.");
        }

        $len = mb_strlen($nome);

        if ($len<3) {
            throw new InvalidArgumentException("descricao deve ter pelo menos 3 caracteres.");
        }

        if ($len>255) {
            throw new InvalidArgumentException("descricao deve ter ao máximo 64 caracteres.");
        }

        $this->descricao=$nome;
    }

    public function jsonSerialize(): array
    {
        return[
            'idCategoria' => $this->getIdCategoria(),
            'nomeCategoria' => $this->getNomeCategoria(),
            'descricao' => $this->getDescricao()
        ];
    }
}