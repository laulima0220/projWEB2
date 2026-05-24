<?php

namespace Api\Services;

use Api\DAO\UsuarioDAO;
use Api\Models\Usuario;
use Api\Http\MeuTokenJWT;
use Api\Http\ErrorResponse;
use stdClass;

class UsuarioService
{
    private UsuarioDAO $usuarioDAO;

    public function __construct(UsuarioDAO $usuarioDAODependency)
    {
        error_log("UsuarioService::__construct()");

        $this->usuarioDAO=$usuarioDAODependency;
    }
    
    public function createService(stdClass $jsonUsuario): Usuario
    {
        error_log("UsuarioService::createService()");

        $usuario = new Usuario();
        $usuario->setNomeUsuario($jsonUsuario->usuario->nomeUsuario);
        $usuario->setEmail($jsonUsuario->usuario->email);
        $usuario->setSenha($jsonUsuario->usuario->senha);
        $usuario->setAdmin($jsonUsuario->usuario->admin);

        $emailExiste=$this->usuarioDAO->findByField(
            'email',
            $usuario->getEmail()
        );

        if(count($emailExiste)>0){
            throw new ErrorResponse(
                400,
                "Email já cadastrado",
                [
                    "message"=>
                        "O email {$usuario->getEmail()} já existe"
                ]
            );
        }

        return $this->usuarioDAO->create($usuario);
    }

    public function loginService(array $jsonUsuario): array
    {
        error_log("UsuarioService::loginService()");

        $usuario = new Usuario();
        $usuario->setEmail($jsonUsuario['usuario']['email']);
        $usuario->setSenha($jsonUsuario['usuario']['senha']);

        $encontrado=$this->usuarioDAO->login($usuario);

        if(!$encontrado){
            throw new ErrorResponse(
                401,
                "Usuário ou senha inválidos",
                [
                    "message" =>
                        "Não foi possível autenticar"
                ]
            );
        }

        $user = [
            "usuario" => [
                "email" =>
                    $encontrado->getEmail(),
                "admin" =>
                    $encontrado->getAdmin(),
                "name" =>
                    $encontrado->getNomeUsuario(),
                "idUsuario" =>
                    $encontrado->getIdUsuario()
            ]
        ];

        return [
            "user" => $user
        ];
    }

    public function findAll(): array
    {
        error_log("UsuarioService::findAll()");
        return $this->usuarioDAO->findAll();
    }

    public function findByIdService(int $idUsuario): Usuario
    {
        error_log("UsuarioService::findByIdService()");

        $usuario=$this->usuarioDAO->findById(
            $idUsuario
        );

        if(!$usuario){
            throw new ErrorResponse(
                404,
                "Usuario não encontrado",
                [
                    "message" =>
                        "Não existe usuário com id {$idUsuario}"
                ]
            );
        }

        return $usuario;
    }

    public function updateService(int $idUsuario, array $requestBody): bool
    {
        error_log("UsuarioService::updateService()");

        $usuarioExiste = $this->usuarioDAO->findById($idUsuario);

        if(!$usuarioExiste){
            throw new ErrorResponse(
                404,
                "Usuario não encontrado",
                [
                    "message" =>
                        "Não existe usuário com id {$idUsuario}"
                ]
            );
        }

        $jsonUsuario=$requestBody['usuario'];

        $usuario = new Usuario();
        $usuario->setIdUsuario($idUsuario);
        $usuario->setNomeUsuario($jsonUsuario['nomeUsuario']);
        $usuario->setEmail($jsonUsuario['email']);
        $usuario->setSenha($jsonUsuario['senha']);
        $usuario->setAdmin($jsonUsuario['admin']);

        return $this->usuarioDAO->update($usuario);
    }

    public function deleteService(int $idUsuario): bool
    {
        error_log("UsuarioService::deleteService()");

        $usuarioExiste = $this->usuarioDAO->findById($idUsuario);

        if(!$usuarioExiste){
            throw new ErrorResponse(
                404, 
                "Usuario não encontrado", 
                [
                    "message" => 
                        "Não existe usuário com id {$idUsuario}."
                ]
            );
        }

        $usuario = new Usuario();
        $usuario->setIdUsuario($idUsuario);

        return $this->usuarioDAO->delete($usuario);
    }

    public function countService(): int
    {
        error_log("UsuarioService::countService()");
        return $this->usuarioDAO->count();
    }
}