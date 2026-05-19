<?php  
namespace Api\Models;
use InvalidArgumentException;
use \JsonSerializable;

class Poema implements JsonSerializable
{
    private int $idPoema;
    private string $titulo;
    private string $conteudo;
    private int $anoPublicacao;
    private Autor $autor;
    private Categoria $categoria;

    public function __construct()
    {
        $this->autor = new Autor();
        $this->categoria = new Categoria();
    }

    public function getIdPoema(): ?int
    {
        return $this->idPoema;
    }

    public function setIdPoema($valor): void
    {
        if(!is_numeric($valor)){
            throw new InvalidArgumentException("idPoema deve ser um número.");
        }
        if ($valor <= 0) {
            throw new \Exception("idPoema deve ser um número inteiro positivo.");
        }
    }

    public function getTitulo(): string{
        return $this->titulo;
    }

    public function setTitulo(string $nome): void
    {
        $nome = trim($nome);
        if (strlen($nome) < 3) {
            throw new \Exception("titulo deve ter pelo menos 3 caracteres.");
        }
        if(strlen($nome)>128)
        {
            throw new \Exception("titulo deve ter ao máximo 128 caracteres.");
        }
        $this->titulo = $nome;
    }

    public function getConteudo(): string
    {
        return $this->conteudo;
    }

    public function setConteudo(string $nome): void
    {
        $nome = trim($nome);
        if (strlen($nome) < 3) {
            throw new \Exception("conteudo deve ter pelo menos 3 caracteres.");
        }
        $this->conteudo = $nome;
    }

    public function getAnoPublicacao(): ?int
    {
        return $this->anoPublicacao;
    }

    public function setAnoPublicacao($valor): void
    {
        if ($valor === null || $valor === '') {
            $this->anoPublicacao = null;
            return;
        }
        if (!is_numeric($valor)) {
            throw new \Exception("anoPublicacao deve ser um número inteiro.");
        }
        if ($valor <= 0) {
            throw new \Exception("anoPublicacao deve ser um número inteiro positivo.");
        }
        if($valor>2026){
            throw new \Exception("anoPublicacao deve ser um ano válido.");
        }
        $this->anoPublicacao = $valor;
    }

    public function getAutor(): Autor
    {
        return $this->autor;
    }

    public function setAutor(Autor $autor): void
    {
        if ($autor->getIdAutor() === null) {
            throw new InvalidArgumentException(
                "Autor inválido."
            );
        }
        $this->autor = $autor;
    }

    public function getCategoria(): Categoria
    {
        if ($autor->getIdCategoria() === null) {
            throw new InvalidArgumentException(
                "Categoria inválida."
            );
        }
        return $this->categoria;
    }

    public function setCategoria(Categoria $categoria): void
    {
        $this->categoria = $categoria;
    }

        public function jsonSerialize(): array
    {
        return [
            'idPoema' => $this->getIdPoema(),
            'titulo' => $this->getTitulo(),
            'conteudo' => $this->getConteudo(),
            'anoPublicacao' => $this->getAnoPublicacao(),
            'autor' => $this->autor(),
            'categoria' => $this->categoria()
        ];
    }
}