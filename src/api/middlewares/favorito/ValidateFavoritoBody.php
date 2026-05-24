<?php
namespace Api\Middlewares\Favorito;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Api\Http\ErrorResponse;

class ValidateFavoritoBody implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        if(!isset($objPHP->favorito)){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message" => "O campo 'favorito' é obrigatório!"
                ]
            );
        }

        $favorito = $objPHP->favorito;

        if(!isset($favorito->dataFavoritado) || trim((string) $favorito->dataFavoritado) === ""){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message" => "O campo 'dataFavoritado' é obrigatório!"
                ]
            );
        }

        if(
            !isset($favorito->usuario) ||
            !isset($favorito->usuario->idUsuario) ||
            !is_int($favorito->usuario->idUsuario) ||
            $favorito->usuario->idUsuario <= 0
        ){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message" => "O campo 'idUsuario' deve ser um número inteiro positivo."
                ]
            );
        }

        if(
            !isset($favorito->poema) ||
            !isset($favorito->poema->idPoema) ||
            !is_int($favorito->poema->idPoema) ||
            $favorito->poema->idPoema <= 0
        ){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message" => "O campo 'idPoema' deve ser um número inteiro positivo."
                ]
            );
        }

        return $handler->handle($request);
    }
}