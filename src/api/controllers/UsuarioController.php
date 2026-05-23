<?php
namespace Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response; 
use Psr\Http\Message\ServerRequestInterface as Request; 
use Api\Services\UsuarioService;

class UsuarioController
{
    private UsuarioService $usuarioService;

    public function __construct(UsuarioService $usuarioServiceDependency)
    {
        error_log("UsuarioController::__construct()");
        $this->usuarioService = $usuarioServiceDependency;
    }

    public function createController(Request $request, Response $response, array $args): Response
    {
        error_log("UsuarioController::createController()");

        $body = $request->getBody()->getContents();
        $objPHP = json_decode($body);

        $resultado = $this->usuarioService->createService($objPHP);

        $resposta = [
            'success' => true,
            'message' => 'Cadastro realizado com sucesso',
            'data' => [
                'usuarios' => [
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
        error_log("UsuarioController::findAllController()");

        $lista=$this->usuarioService->findAll();

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'usuarios' => $lista
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
        error_log("UsuarioController::findByIdController()");

        $id=(int) $args['idUsuario'];

        $usuario=$this->usuarioService->findByIdService($id);

        $resposta = [
            'success' => true,
            'message' => 'Executado com sucesso',
            'data' => [
                'usuarios' => [
                    $usuario
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
        error_log("UsuarioController::countController()");

        $qtd=$this->usuarioService->countService();

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
        error_log("UsuarioController::updateController()");

        $id = (int) $args['idUsuario'];

        $body = $request->getBody()->getContents();
        $arrayPHP = json_decode($body, true);

        $resultado = $this->usuarioService->updateService($id, $arrayPHP);

        $resposta = [
            'success' => true,
            'message' => 'Atualizado com sucesso',
            'data' => [
                'usuarios' => [
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
        error_log("UsuarioController::deleteController()");

        $id=(int) $args['idUsuario'];

        $excluiu=$this->usuarioService->deleteService($id);

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