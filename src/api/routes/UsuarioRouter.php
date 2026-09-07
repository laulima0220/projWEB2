<?php
namespace Api\Routes;

use Slim\App;
use Api\Controllers\UsuarioController;
use Api\Middlewares\Usuario\ValidateUsuarioBody;
use Api\Middlewares\Usuario\ValidateUsuarioId;
use Api\Middlewares\Usuario\ValidateUsuarioLoginBody;
use Api\Middlewares\Usuario\ValidateUsuarioToken;
use Api\Middlewares\Usuario\ValidateAdministrador;

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
        error_log("🔴 FuncionarioController::setupRoutes()");

        $this->app->post(
            '/usuarios/login',
            [$this->controller, 'loginController']
        )
            ->add(ValidateUsuarioLoginBody::class);


        $this->app->post(
            '/usuarios',
            [$this->controller, 'createController']
        )
            ->add(ValidateUsuarioBody::class)
            ->add(ValidateUsuarioToken::class)
            ->add(ValidateAdministrador::class)
            ->add(ValidateUsuarioToken::class);   


        $this->app->put(
            '/usuarios/{idUsuario}',
            [$this->controller, 'updateController']
        )
        ->add(ValidateUsuarioBody::class)
        ->add(ValidateUsuarioId::class)
        ->add(ValidateAdministrador::class)
        ->add(ValidateUsuarioToken::class);
        

        $this->app->delete(
            '/usuarios/{idUsuario}',
            [$this->controller, 'deleteController']
        )
        ->add(ValidateUsuarioId::class)
        ->add(ValidateAdministrador::class)
        ->add(ValidateUsuarioToken::class);


        $this->app->get(
            '/usuarios',
            [$this->controller, 'findAllController']
        )->add(ValidateUsuarioToken::class);


        $this->app->get(
            '/usuarios/count',
            [$this->controller, 'countController']
        )->add(ValidateUsuarioToken::class);  

        
        $this->app->get(
            '/usuarios/{idUsuario}',
            [$this->controller, 'findByIdController']
        )
            ->add(ValidateUsuarioId::class)
            ->add(ValidateUsuarioToken::class);
    }
}