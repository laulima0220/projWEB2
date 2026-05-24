<?php
namespace Api\Routes;

use Slim\App;
use Api\Controllers\FavoritoController;
use Api\Middlewares\Favorito\ValidateFavoritoBody;
use Api\Middlewares\Favorito\ValidateFavoritoId;

class FavoritoRouter
{
    private App $app;

    private FavoritoController $controller;

    public function __construct(App $app, FavoritoController $controller)
    {
        $this->app = $app;
        $this->controller = $controller;
    }

    public function setupRoutes(): void
    {
        $this->app->post(
            '/favoritos',
            [$this->controller, 'createController']
        )
        ->add(ValidateFavoritoBody::class);    


        $this->app->put(
            '/favoritos/{idFavorito}',
            [$this->controller, 'updateController']
        )
        ->add(ValidateFavoritoBody::class)
        ->add(ValidateFavoritoId::class);
        

        $this->app->delete(
            '/favoritos/{idFavorito}',
            [$this->controller, 'deleteController']
        )
        ->add(ValidateFavoritoId::class);


        $this->app->get(
            '/favoritos',
            [$this->controller, 'findAllController']
        );


        $this->app->get(
            '/favoritos/count',
            [$this->controller, 'countController']
        );  

        
        $this->app->get(
            '/favoritos/{idFavorito}',
            [$this->controller, 'findByIdController']
        )
        ->add(ValidateFavoritoId::class);
    }
}