<?php
namespace Api\Middlewares\Usuario;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Api\Http\ErrorResponse;

class ValidateUsuarioBody implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        if(!isset($objPHP->usuario)){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message" => "O campo 'usuario' é obrigatório!"
                ]
            );
        }

        $usuario = $objPHP->usuario;

        $camposObrigatorios = [
            "nomeUsuario",
            "email",
            "senha",
            "admin"
        ];

        foreach($camposObrigatorios as $campo){
            if(
                !isset($usuario->$campo) ||
                $usuario->$campo === "" ||
                $usuario->$campo === null
            ){
                throw new ErrorResponse(
                    400,
                    "Erro na validação de dados",
                    [
                        "message" => "O campo '{$campo}' é obrigatório!"
                    ]
                );
            }
        }

        if(!in_array($usuario->admin, [0, 1], true)){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message" => "O campo 'admin' deve ser 0 ou 1."
                ]
            );
        }

        return $handler->handle($request);
    }
}