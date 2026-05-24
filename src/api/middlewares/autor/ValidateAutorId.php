<?php
namespace Api\Middlewares\Autor;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Routing\RouteContext;
use Api\Http\ErrorResponse;

class ValidateAutorId implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        $routeArgs = $route->getArguments();

        if(!isset($routeArgs['idAutor']) || $routeArgs['idAutor'] === ""){
            throw new ErrorResponse(
                400,
                "Erro na validação de dados",
                [
                    "message" => "O parâmetro 'idAutor' é obrigatório!"
                ]
            );
        }

        return $handler->handle($request);
    }
}