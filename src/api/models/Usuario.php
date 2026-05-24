<?php
namespace Api\Models;
use InvalidArgumentException;
use \JsonSerializable;

class Usuario implements JsonSerializable
{
    private ?int $idUsuario = null;
    private ?string $nomeUsuario = null;
    private ?string $email = null;
    private ?string $senha = null;
    private ?int $admin = null;

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
        if($valor <= 0){
            throw new InvalidArgumentException("idUsuario deve ser maior que zero.");
        }

        $this->idUsuario = (int) $valor;
    }

    public function getNomeUsuario(): ?string
    {
        return $this->nomeUsuario;
    }

    public function setNomeUsuario(string $nome): void
    {
        $nome = trim($nome);
        if(strlen($nome) < 3){
            throw new InvalidArgumentException("nomeUsuario deve ter pelo menos 3 caracteres.");
        }
        $this->nomeUsuario = $nome;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $email = trim($email);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new InvalidArgumentException("email inválido.");
        }
        $this->email = $email;
    }

    public function getSenha(): ?string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): void
    {
        $senha = trim($senha);

        if(strlen($senha) < 6){
            throw new InvalidArgumentException("senha deve ter pelo menos 6 caracteres.");
        }
        if(!preg_match("/[A-Z]/", $senha)){
            throw new InvalidArgumentException("senha deve conter pelo menos uma letra maiúscula.");
        }
        if(!preg_match("/[0-9]/", $senha)){
            throw new InvalidArgumentException("senha deve conter pelo menos um número.");
        }
        if(!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $senha)){
            throw new InvalidArgumentException("senha deve conter pelo menos um caractere especial.");
        }
        $this->senha = $senha;
    }

    public function getAdmin(): ?int
    {
        return $this->admin;
    }

    public function setAdmin(int $valor): void
    {
        if($valor !== 0 && $valor !== 1){
            throw new InvalidArgumentException("admin deve ser 0 ou 1.");
        }
        $this->admin = $valor;
    }

    public function jsonSerialize(): array
    {
        return [
            'idUsuario'   => $this->getIdUsuario(),
            'nomeUsuario' => $this->getNomeUsuario(),
            'email'       => $this->getEmail(),
            'admin'       => $this->getAdmin()
        ];
    }
}