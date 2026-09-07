<?php

namespace Api\Middlewares\Usuario;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Api\Http\ErrorResponse;

class ValidateUsuarioLoginBody implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        error_log("🟠  ValidateUsuarioLoginBody::process()");

        $body = $request->getBody()->getContents();

        $objPHP = json_decode($body);

        if (!isset($objPHP->usuario)) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "O campo 'usuario' é obrigatório!"
                ]
            );
        }

        $usuario = $objPHP->usuario;

        if (
            !isset($usuario->email) ||
            empty(trim($usuario->email))
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "O campo 'email' é obrigatório!"
                ]
            );
        }

        if (
            !filter_var(
                $usuario->email,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "Email inválido!"
                ]
            );
        }

        if (
            !isset($usuario->senha) ||
            empty(trim($usuario->senha))
        ) {
            throw new ErrorResponse(
                httpCode: 400,
                message: "Erro na validação de dados",
                error: [
                    "message" => "O campo 'senha' é obrigatório!"
                ]
            );
        }

        return $handler->handle($request);
    }
}