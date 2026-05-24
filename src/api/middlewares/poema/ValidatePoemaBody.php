<?php
namespace Api\Middlewares\Poema;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Api\Http\ErrorResponse;

class ValidatePoemaBody implements MiddlewareInterface 
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);
        
        if(!isset($objPHP->poema)){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message"=> "O campo 'poema' é obrigatório!"
                ]
            );
        }

        $poema = $objPHP->poema;

        $camposObrigatorios = [
            "titulo",
            "conteudo",
            "anoPublicacao",
        ];

        foreach($camposObrigatorios as $campo){
            if(
                !isset($poema->$campo) ||
                $poema->$campo === "" ||
                $poema->$campo === null
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

        if(
            !isset($poema->autor) ||
            !isset($poema->autor->idAutor) ||
            !is_int($poema->autor->idAutor) ||
            $poema->autor->idAutor <= 0
        ){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message" => "O campo 'idAutor' deve ser um número inteiro positivo."
                ]
            );
        }

        if(
            !isset($poema->categoria) ||
            !isset($poema->categoria->idCategoria) ||
            !is_int($poema->categoria->idCategoria) ||
            $poema->categoria->idCategoria <= 0
        ){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message" => "O campo 'idCategoria' deve ser um número inteiro positivo."
                ]
            );
        }

        return $handler->handle($request);
    }
}