<?php  
namespace Api\Models;
use InvalidArgumentException;
use \JsonSerializable;

class Autor implements JsonSerializable
{
    private int $idAutor;
    private string $nomeAutor;
    private string $nacionalidade;
    private string $biografia;

    public function __construct()
    {

    }

    public function getIdAutor(): ?int
    {
        return $this->idAutor;
    }

    public function setIdAutor(int $value): void
    {
        if(!is_int($value)){
            throw new InvalidArgumentException("idAutor deve ser um número inteiro.");
        }

        if($value<=0){
            throw new InvalidArgumentException("idAutor deve ser um número positivo.");
        }

        $this->idAutor=$value;
    }

    public function getNomeAutor(): string
    {
        return $this->nomeAutor;
    }

    public function setNomeAutor(string $value): void
    {
        $nome=trim($value);

        if ($nome==='') {
            throw new InvalidArgumentException("nomeAutor não pode ser vazio.");
        }

        $len = mb_strlen($nome);

        if ($len<3) {
            throw new InvalidArgumentException("nomeAutor deve ter pelo menos 3 caracteres.");
        }

        if ($len>128) {
            throw new InvalidArgumentException("nomeAutor deve ter ao máximo 128 caracteres.");
        }

        $this->nomeAutor=$nome;
    }

    public function getNacionalidade(): ?string
    {
        return $this->nacionalidade;
    }

    public function setNacionalidade(string $value): void
    {
        $nome=trim($value);

        if ($nome==='') {
            throw new InvalidArgumentException("nacionalidade não pode ser vazio.");
        }

        $len = mb_strlen($nome);

        if ($len<3) {
            throw new InvalidArgumentException("nacionalidade deve ter pelo menos 3 caracteres.");
        }

        if ($len>64) {
            throw new InvalidArgumentException("nacionalidade deve ter ao máximo 64 caracteres.");
        }

        $this->nacionalidade=$nome;
    }

    public function getBiografia(): ?string
    {
        return $this->biografia;
    }

    public function setBiografia(string $value): void
    {
        $nome=trim($value);

        if ($nome==='') {
            throw new InvalidArgumentException("biografia não pode ser vazio.");
        }

        $len = mb_strlen($nome);

        if ($len<10) {
            throw new InvalidArgumentException("biografia deve ter pelo menos 10 caracteres.");
        }

        if ($len>255) {
            throw new InvalidArgumentException("biografia deve ter ao máximo 255 caracteres.");
        }

        $this->biografia=$nome;
    }

    public function jsonSerialize(): array
    {
        return[
            'idAutor' => $this->getIdAutor(),
            'nomeAutor' => $this->getNomeAutor(),
            'nacionalidade' => $this->getNacionalidade(),
            'biografia' => $this->getBiografia()
        ];
    }
}