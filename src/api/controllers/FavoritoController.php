<?php
namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response; 
use Psr\Http\Message\ServerRequestInterface as Request; 
use Api\Services\FavoritoService;

class FavoritoController
{
    private FavoritoService $favoritoService;

    public function __construct(FavoritoService $favoritoServiceDependency)
    {
        error_log("FavoritoController::__construct()");
        $this->favoritoService=$favoritoServiceDependency;
    }

    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("FavoritoController::createController()");

        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        $resultado = $this->favoritoService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'favoritos' => [
                    $resultado
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
        error_log("FavoritoController::findAllController()");

        $lista=$this->favoritoService->findAll();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'favoritos' => $lista
            ]
        ];

        $response->getBody()->write(
            json_encode($resposta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
    
    public function findByIdController(Request $request, Response $response, array $args): Response
    {
        error_log("favoritoController::findByIdController()");

        $id=(int) $args['idFavorito'];

        $favorito=$this->favoritoService->findByIdService($id);

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'favoritos' => [
                    $favorito
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
        error_log("FavoritoController::countController()");

        $qtd=$this->favoritoService->countService();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'count' => $qtd
            ]
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }    

    public function updateController(Request $request, Response $response, array $args): Response
    {
        error_log("FavoritoController::updateController()");

        $id = (int) $args['idFavorito'];

        $body = $request->getBody()->getContents();
        $arrayPHP = json_decode($body, true);

        $resultado = $this->favoritoService->updateService($id, $arrayPHP);

        $resposta = [
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'favoritos' => [
                    $resultado
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
        error_log("FavoritoController::deleteController()");

        $id=(int) $args['idFavorito'];

        $excluiu=$this->favoritoService->deleteService($id);

        $status=$excluiu ? 204 : 404;
        $mensagem = $excluiu
            ? 'Excluído com sucesso'
            : 'Funcionário não encontrado';

        $resposta=[
            'success' => $excluiu,
            'message' => $mensagem
        ];

        $response->getBody()->write(json_encode($resposta));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }
}