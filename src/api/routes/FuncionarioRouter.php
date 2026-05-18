<?php

namespace Api\Routes;

use Slim\App;
use Api\Controllers\FuncionarioController;
use Api\Middlewares\Funcionario\ValidateFuncionarioBody;
use Api\Middlewares\Funcionario\ValidateFuncionarioId;

/**
 * Classe responsável por registrar as rotas do recurso Funcionário.
 *
 * Endpoints disponíveis:
 * - POST   /funcionarios
 * - PUT    /funcionarios/{idFuncionario}
 * - DELETE /funcionarios/{idFuncionario}
 * - GET    /funcionarios
 * - GET    /funcionarios/count
 * - GET    /funcionarios/{idFuncionario}
 */
class FuncionarioRouter
{
    /**
     * Instância principal da aplicação Slim.
     *
     * @var App
     */
    private App $app;

    /**
     * Controller responsável pelas regras de negócio.
     *
     * @var FuncionarioController
     */
    private FuncionarioController $controller;

    /**
     * Recebe as dependências necessárias.
     *
     * @param App $app Aplicação Slim.
     * @param FuncionarioController $controller Controller de funcionário.
     */
    public function __construct(App $app, FuncionarioController $controller)
    {
        $this->app = $app;
        $this->controller = $controller;
    }

    /**
     * Registra todas as rotas relacionadas ao recurso funcionário.
     *
     * IMPORTANTE:
     * No Slim Framework, os middlewares executam
     * na ordem inversa em que são adicionados.
     *
     * Exemplo:
     * ->add(A)->add(B)
     *
     * Ordem real:
     * 1. B
     * 2. A
     * 3. Controller
     *
     * @return void
     */
    public function setupRoutes(): void
    {
        /**
         * =====================================================
         * POST /funcionarios
         * =====================================================
         * Cria um novo funcionário.
         *
         * Body:
         * {
         *   "funcionario": {
         *     "nomeFuncionario": "João",
         *     "email": "joao@email.com",
         *     "senha": "123456",
         *     "recebeValeTransporte": 1,
         *     "cargo": {
         *       "idCargo": 1
         *     }
         *   }
         * }
         *
         * Ordem de execução:
         * 1. ValidateFuncionarioBody
         * 2. createController
         */
        $this->app->post(
            '/funcionarios',
            [$this->controller, 'createController']
        )
        ->add(ValidateFuncionarioBody::class);

        /**
         * =====================================================
         * PUT /funcionarios/{idFuncionario}
         * =====================================================
         * Atualiza um funcionário existente.
         *
         * Ordem de execução:
         * 1. ValidateFuncionarioId
         * 2. ValidateFuncionarioBody
         * 3. updateController
         */
        $this->app->put(
            '/funcionarios/{idFuncionario}',
            [$this->controller, 'updateController']
        )
        ->add(ValidateFuncionarioBody::class)
        ->add(ValidateFuncionarioId::class);

        /**
         * =====================================================
         * DELETE /funcionarios/{idFuncionario}
         * =====================================================
         * Remove um funcionário pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateFuncionarioId
         * 2. deleteController
         */
        $this->app->delete(
            '/funcionarios/{idFuncionario}',
            [$this->controller, 'deleteController']
        )
        ->add(ValidateFuncionarioId::class);

        /**
         * =====================================================
         * GET /funcionarios
         * =====================================================
         * Lista todos os funcionários.
         *
         * Ordem de execução:
         * 1. findAllController
         */
        $this->app->get(
            '/funcionarios',
            [$this->controller, 'findAllController']
        );

        /**
         * =====================================================
         * GET /funcionarios/count
         * =====================================================
         * Retorna a quantidade total de funcionários.
         *
         * Ordem de execução:
         * 1. countController
         */
        $this->app->get(
            '/funcionarios/count',
            [$this->controller, 'countController']
        );

        /**
         * =====================================================
         * GET /funcionarios/{idFuncionario}
         * =====================================================
         * Busca um funcionário pelo ID.
         *
         * Ordem de execução:
         * 1. ValidateFuncionarioId
         * 2. findByIdController
         */
        $this->app->get(
            '/funcionarios/{idFuncionario}',
            [$this->controller, 'findByIdController']
        )
        ->add(ValidateFuncionarioId::class);
    }
}