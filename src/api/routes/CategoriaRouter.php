<?php
namespace Api\Routes;

use Slim\App;
use Api\Controllers\CategoriaController;
use Api\Middlewares\Categoria\ValidateCategoriaBody;
use Api\Middlewares\Categoria\ValidateCategoriaId;


class CategoriaRouter
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app=$app;
    }

    public function setupRoutes():void
    {
        $this->app->post(
            '/categorias',
            [CategoriaController::class, 'createController']
        )
            ->add(ValidateCategoriaBody::class);


        $this->app->get(
            '/categorias',
            [CategoriaController::class, 'findAllController']
        );


        $this->app->get(
            '/categorias/count',
            [CategoriaController::class, 'countController']
        );


        $this->app->get(
            '/categorias/{idCategoria}',
            [CategoriaController::class, 'findByIdController']
        )
            ->add(ValidadeCategoriaId::class);


        $this->app->put(
            '/categorias/{idCategoria}',
            [CategoriaController::class, 'updateController']
        )
            ->add(ValidadeCategoriaBody::class)
            ->add(ValidadeCategoriaId::class);


        $this->app->delete(
            '/categorias/{idCategoria}',
            [CategoriaController::class, 'deleteController']
        )
            ->add(ValidadeCategoriaId::class);
    }
}