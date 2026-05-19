<?php
namespace Api\Models;
use Api\Models\Usuario;
use Api\Models\Poema;
use InvalidArgumentException;
use \JsonSerializable;

class Favorito implements JsonSerializable
{
    private int $idFavorito;
    private Usuario $usuario;
    private Poema $poema;
    private string $dataFavoritado;

    public function __construct(){
        $this->usuario = new Usuario();
        $this->poema = new Poema();
    }

    public function getIdFavorito(): ?int
    {
        return $this->idFavorito;
    }

    public function setIdFavorito($valor): void
    {
        if (!is_numeric($valor)) {
            throw new \Exception("idFavorito deve ser um número inteiro.");
        }
        if ($valor <= 0) {
            throw new \Exception("idFavorito deve ser um número inteiro positivo.");
        }
        $this->idFavorito = $valor;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void
    {
        if ($usuario->getIdUsuario() === null) {
            throw new InvalidArgumentException(
                "Usuário inválido."
            );
        }
        $this->usuario = $usuario; 
    }

    public function getPoema(): Poema
    {
        return $this->poema;
    } 

    public function setPoema(Poema $poema): void
    {
        if ($poema->getIdPoema() === null) {
            throw new InvalidArgumentException(
                "Poema inválido."
            );
        }
        $this->poema = $poema;
    }

    public function getDataFavoritado(): ?string
    {
        return $this->dataFavoritado;
    }

    public function setDataFavoritado(string $valor): void
    {
        $valor = trim($valor);

        if ($valor === '') {
            throw new InvalidArgumentException("dataFavoritado não pode ser vazia.");
        }

        if (strlen($valor) != 10) {
            throw new InvalidArgumentException("Data inválida.");
        }

        $this->dataFavoritado = $valor;
    }

    public function jsonSerialize(): array
    {
        return [
            'idFavorito'=>$this->getIdFavorito(),
            'usuario'=>$this->getUsuario(),
            'poema'=>$this->getPoema(),
            'dataFavoritado'=>$this->getDataFavoritado()
        ];
    }
}