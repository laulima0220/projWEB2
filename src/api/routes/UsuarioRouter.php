<?php
namespace Api\Routes;

use Slim\App;
use Api\Controllers\UsuarioController;
use Api\Middlewares\Usuario\ValidateUsuarioBody;
use Api\Middlewares\Usuario\ValidateUsuarioId;

class UsuarioRouter
{
    private App $app;

    private UsuarioController $controller;

    public function __construct(App $app, UsuarioController $controller)
    {
        $this->app = $app;
        $this->controller = $controller;
    }

    public function setupRoutes(): void
    {
        $this->app->post(
            '/usuarios',
            [$this->controller, 'createController']
        )
        ->add(ValidateUsuarioBody::class);    


        $this->app->put(
            '/usuarios/{idUsuario}',
            [$this->controller, 'updateController']
        )
        ->add(ValidateUsuarioBody::class)
        ->add(ValidateUsuarioId::class);
        

        $this->app->delete(
            '/usuarios/{idUsuario}',
            [$this->controller, 'deleteController']
        )
        ->add(ValidateUsuarioId::class);


        $this->app->get(
            '/usuarios',
            [$this->controller, 'findAllController']
        );


        $this->app->get(
            '/usuarios/count',
            [$this->controller, 'countController']
        );  

        
        $this->app->get(
            '/usuarios/{idUsuario}',
            [$this->controller, 'findByIdController']
        )
        ->add(ValidateUsuarioId::class);
    }
}