<?php
namespace Api\Routes;

use Slim\App;
use Api\Controllers\AutorController;
use Api\Middlewares\Autor\ValidateAutorBody;
use Api\Middlewares\Autor\ValidateAutorId;


class AutorRouter
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app=$app;
    }

    public function setupRoutes():void
    {
        $this->app->post(
            '/autores',
            [AutorController::class, 'createController']
        )
            ->add(ValidateAutorBody::class);


        $this->app->get(
            '/autores',
            [AutorController::class, 'findAllController']
        );


        $this->app->get(
            '/autores/count',
            [AutorController::class, 'countController']
        );


        $this->app->get(
            '/autores/{idAutor}',
            [AutorController::class, 'findByIdController']
        )
            ->add(ValidateAutorId::class);


        $this->app->put(
            '/autores/{idAutor}',
            [AutorController::class, 'updateController']
        )
            ->add(ValidateAutorBody::class)
            ->add(ValidateAutorId::class);


        $this->app->delete(
            '/autores/{idAutor}',
            [AutorController::class, 'deleteController']
        )
            ->add(ValidateAutorId::class);       
    }
}