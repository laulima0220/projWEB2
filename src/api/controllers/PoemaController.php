<?php
namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response; 
use Psr\Http\Message\ServerRequestInterface as Request; 
use Api\Services\PoemaService;

class PoemaController
{
    private PoemaService $poemaService;

    public function __construct(PoemaService $poemaServiceDependency)
    {
        error_log("PoemaController::__construct()");
        $this->poemaService = $poemaServiceDependency;
    }

    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("PoemaController::createController()");

        $body=$request->getBody()->getContents();
        $objPHP=json_decode($body);

        $resultado=$this->poemaService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'poemas' => [
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
        error_log("PoemaController::findAllController()");

        $lista=$this->poemaService->findAll();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'poemas' => $lista
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
        error_log("PoemaController::findByIdController()");

        $id=(int) $args['idPoema'];

        $poema=$this->poemaService->findByIdService($id);

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'poemas' => [
                    $poema
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
        error_log("PoemaController::countController()");

        $qtd=$this->poemaService->countService();

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
        error_log("PoemaController::updateController()");

        $id=(int) $args['idPoema'];

        $body = $request->getBody()->getContents();
        $arrayPHP = json_decode($body, true);

        $resultado = $this->poemaService->updateService($id, $arrayPHP);
        
        $resposta = [
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'poemas' => [
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
        error_log("PoemaController::deleteController()");

        $id=(int) $args['idPoema'];

        $excluiu=$this->poemaService->deleteService($id);

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