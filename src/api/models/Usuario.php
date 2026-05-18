<?php
namespace Api\Models;
use InvalidArgumentException;
use \JsonSerializable;

class Usuario implements JsonSerializable
{
    private int $idUsuario;
    private string $nomeUsuario;
    private string $email;
    private string $senha;
    private int $admin;

    public function __construct()
    {

    }

    public function getIdUsuario(): ?int
    {
        return $this->idUsuario;
    } 

    public function setIdUsuario($valor): void
    {
        if(!is_numeric($valor)){
            throw new InvalidArgumentException("idUsuario deve ser um número.");
        }
        if($valor<=0){
            throw new \Exception("idUsuario deve ser um número inteiro.");
        }

        $this->idUsuario=$valor;
    }
    
    public function getNomeUsuario(): string
    {
        return $this->nomeUsuario;
    }

    public function setNomeUsuario(string $nome): void
    {
        $nome=trim($nome);
        if(strlen($nome)<3){
            throw new \Exception("nomeUsuario deve ter pelo menos 3 caracteres.");
        }
        $this->nomeUsuario=$nome;
    }

    public function getEmail(string $email): string
    {
        return $this->email;
    }

        public function setEmail(string $email): void
    {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("email inválido.");
        }
        $this->email = $email;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): void
    {
        $senha = trim($senha);

        if (strlen($senha) < 6) {
            throw new \Exception("senha deve ter pelo menos 6 caracteres.");
        }
        if (!preg_match("/[A-Z]/", $senha)) {
            throw new \Exception("senha deve conter pelo menos uma letra maiúscula.");
        }
        if (!preg_match("/[0-9]/", $senha)) {
            throw new \Exception("senha deve conter pelo menos um número.");
        }
        if (!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $senha)) {
            throw new \Exception("senha deve conter pelo menos um caractere especial.");
        }
        $this->senha = $senha; 
    }

    public function getAdmin(): int
    {
        return $this->admin;
    }

    public function setAdmin(int $valor): void
    {
        if ($valor !== 0 && $valor !== 1) {
            throw new \Exception("admin deve ser 0 ou 1.");
        }
        $this->recebeValeTransporte = $valor;
    }

    public function jsonSerialize(): array
    {
        return [
            'idUsuario' => $this->getIdUsuario(),
            'nomeUsuario' => $this->getNomeUsuario(),
            'email' => $this->getEmail(),
            'admin' => $this->getAdmin()
        ];
    }
}