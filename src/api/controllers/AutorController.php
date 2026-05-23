<?php
namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\AutorService;

class AutorController
{
    private AutorService $autorService;

    public function __construct(AutorService $autorServiceDependency)
    {
        error_log("AutorController::__construct()");
        $this->autorService=$autorServiceDependency;
    }

    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("AutorController::createController()");

        $body=$request->getBody()->getContents();
        $objPHP=json_decode($body);

        $novoAutor=$this->autorService->createService($objPHP);

        $resposta=[
            'success'=> true,
            'message'=> 'Cadastro realizado com sucesso',
            'data'=>[
                'autores'=>[
                    [
                        'idAutor'=>$novoAutor->getIdAutor(),
                        'nomeAutor'=>$novoAutor->getNomeAutor(),
                        'nacionalidade'=>$novoAutor->getNacionalidade(),
                        'biografia'=>$novoAutor->getBiografia()
                    ]
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }

    public function findAllController(Request $request, Response $response, array $args): Response
    {
        error_log("AutorController::findAllController()");

        $autores=$this->autorService->findAllService();

        $resposta=[
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'autores' => $autores
            ]
        ];
        
        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        error_log("AutorController::findByIdController()");

        $idAutor=(int) $args['idAutor'];
        $autor=$this->autorService->findByIdService($idAutor);

        $resposta=[
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'autores' => $autor
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("AutorController::updateController()");

        $idAutor=(int) $args['idAutor'];

        $body=$request->getBody()->getContents();
        $objPHP=json_decode($body);

        $nomeAutor=$objPHP->autor->nomeAutor;
        $nacionalidade=$objPHP->autor->nacionalidade;
        $biografia=$objPHP->autor->biografia;

        $this->autorService->updateService($idAutor, $nomeAutor, $nacionalidade, $biografia);

        $resposta=[
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'autores' => [
                    [
                        'idAutor' => $idAutor,
                        'nomeAutor' => $nomeAutor,
                        'nacionalidade'=>$nacionalidade,
                        'biografia'=>$biografia
                    ]
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function deleteController(Request $request, Response $response, array $args): Response
    {
        error_log("AutorController::deleteController()");

        $idAutor=(int) $args['idAutor'];

        $this->autorService->deleteService($idAutor);

        $resposta=[
            'success' => true,
            'message' => 'Excluído com sucesso',
            'data' => [
                'autores' => [
                    [
                        'idAutor' => $idAutor
                    ]
                ]
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function countController(Request $request, Response $response, array $args): Response
    {
        error_log("AutorController::countController()");

        $total=$this->autorService->countService();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'count' => $total
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}