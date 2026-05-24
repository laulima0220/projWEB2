<?php
namespace Api\Middlewares\Autor;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Api\Http\ErrorResponse;

class ValidateAutorBody implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $body=$request->getBody()->getContents();
        $objPHP = json_decode($body);

        if(!isset($objPHP->autor)){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message" => "O campo 'autor' é obrigatório!"
                ]
            );
        }

        $autor=$objPHP->autor;

        if(!isset($autor->nomeAutor) || trim((string) $autor->nomeAutor) === ""){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message" => "O campo 'nomeAutor' é obrigatório!"
                ]
            );
        }

        return $handler->handle($request);
    }
}