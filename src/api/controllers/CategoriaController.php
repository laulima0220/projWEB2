<?php
namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Api\Services\CategoriaService;

class CategoriaController
{
    private CategoriaService $categoriaService;

    public function __construct(CategoriaService $categoriaServiceDependency)
    {
        error_log("CategoriaController::__construct()");
        $this->categoriaService=$categoriaServiceDependency;
    }

    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("CategoriaController::createController()");

        $body=$request->getBody()->getContents();
        $objPHP=json_decode($body);

        $novaCategoria=$this->categoriaService->createService($objPHP);

        $resposta=[
            'success'=> true,
            'message'=> 'Cadastro realizado com sucesso',
            'data'=>[
                'categorias'=>[
                    [
                        'idCategoria'=>$novaCategoria->getIdCategoria(),
                        'nomeCategoria'=>$novaCategoria->getNomeCategoria(),
                        'descricao'=>$novaCategoria->getDescricao()
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
        error_log("CategoriaController::findAllController()");

        $categorias = $this->categoriaService->findAllService();

        $resposta = [
            'success' => true,
            'message' => 'Busca realizada com sucesso',
            'data' => [
                'categorias' => $categorias
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        error_log("CategoriaController::findByIdController()");

        $idCategoria=(int) $args['idCategoria'];
        $categoria=$this->categoriaService->findByIdService($idCategoria);

        $resposta=[
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'categorias' => $categoria
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }

    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("CategoriaController::updateController()");

        $idCategoria=(int) $args['idCategoria'];

        $body=$request->getBody()->getContents();
        $objPHP=json_decode($body);

        $nomeCategoria=$objPHP->categoria->nomeCategoria;
        $descricao=$objPHP->categoria->descricao;

        $this->categoriaService->updateService($idCategoria, $nomeCategoria, $descricao);

        $resposta=[
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'categorias' => [
                    [
                        'idCategoria' => $idCategoria,
                        'nomeCategoria' => $nomeCategoria,
                        'descricao'=>$descricao
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
        error_log("CategoriaController::deleteController()");

        $idCategoria=(int) $args['idCategoria'];

        $this->categoriaService->deleteService($idCategoria);

        $resposta=[
            'success' => true,
            'message' => 'Excluído com sucesso',
            'data' => [
                'categorias' => [
                    [
                        'idCategoria' => $idCategoria
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
        error_log("CategoriaController::countController()");

        $total=$this->categoriaService->countService();

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