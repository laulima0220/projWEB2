<?php
namespace Api\Routes;

use Slim\App;
use Api\Controllers\PoemaController;
use Api\Middlewares\Poema\ValidatePoemaBody;
use Api\Middlewares\Poema\ValidatePoemaId;

class PoemaRouter
{
    private App $app;

    private PoemaController $controller;

    public function __construct(App $app, PoemaController $controller)
    {
        $this->app = $app;
        $this->controller = $controller;
    }

    public function setupRoutes(): void
    {
        $this->app->post(
            '/poemas',
            [$this->controller, 'createController']
        )
        ->add(ValidatePoemaBody::class);    


        $this->app->put(
            '/poemas/{idPoema}',
            [$this->controller, 'updateController']
        )
        ->add(ValidatePoemaBody::class)
        ->add(ValidatePoemaId::class);
        

        $this->app->delete(
            '/poemas/{idPoema}',
            [$this->controller, 'deleteController']
        )
        ->add(ValidatePoemaId::class);


        $this->app->get(
            '/poemas',
            [$this->controller, 'findAllController']
        );


        $this->app->get(
            '/poemas/count',
            [$this->controller, 'countController']
        );  

        
        $this->app->get(
            '/poemas/{idPoema}',
            [$this->controller, 'findByIdController']
        )
        ->add(ValidatePoemaId::class);
    }
}