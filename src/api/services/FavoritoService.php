<?php
namespace Api\Services;

use Api\DAO\FavoritoDAO;
use Api\DAO\UsuarioDAO;
use Api\DAO\PoemaDAO;
use Api\Models\Favorito;
use Api\Models\Usuario;
use Api\Models\Poema;
use Api\Http\MeuTokenJWT;
use Api\Http\ErrorResponse;
use stdClass;

class FavoritoService
{
    private FavoritoDAO $favoritoDAO;
    private UsuarioDAO $usuarioDAO;
    private PoemaDAO $poemaDAO;

    public function __construct(
        FavoritoDAO $favoritoDAODependency,
        UsuarioDAO $usuarioDAODependency,
        PoemaDAO $poemaDAODependency
    ){
        error_log("FavoritoService::__construct()");

        $this->favoritoDAO=$favoritoDAODependency;
        $this->usuarioDAO=$usuarioDAODependency;
        $this->poemaDAO=$poemaDAODependency;
    }

        public function createService(stdClass $jsonFavorito): Favorito
    {
        error_log("FavoritoService::createService()");

        $usuario = new Usuario();
        $usuario->setIdUsuario($jsonFavorito->favorito->usuario->idUsuario);

        $usuarioExiste = $this->usuarioDAO->findById($usuario->getIdUsuario());

        if(!$usuarioExiste){
            throw new ErrorResponse(404, "Usuário não encontrado", [
                "message" => "Não existe usuário com id {$usuario->getIdUsuario()}"
            ]);
        }

        $poema = new Poema();
        $poema->setIdPoema($jsonFavorito->favorito->poema->idPoema);

        $poemaExiste = $this->poemaDAO->findById($poema->getIdPoema());

        if(!$poemaExiste){
            throw new ErrorResponse(
                404, 
                "Poema não encontrado", 
                [
                    "message" => 
                        "Não existe poema com id {$poema->getIdPoema()}"
                ]
            );
        }

        $jaFavoritado = $this->favoritoDAO->findByUsuarioEPoema(
            $usuarioExiste->getIdUsuario(),
            $poemaExiste->getIdPoema()
        );

        if($jaFavoritado){
            throw new ErrorResponse(400, "Favorito já existe", [
                "message" => "O usuário já favoritou esse poema."
            ]);
        }

        $favorito = new Favorito();
        $favorito->setUsuario($usuarioExiste);
        $favorito->setPoema($poemaExiste);
        $favorito->setDataFavoritado($jsonFavorito->favorito->dataFavoritado);

        return $this->favoritoDAO->create($favorito);
    }

    public function findAll(): array
    {
        error_log("FavoritoService::findAll()");
        return $this->favoritoDAO->findAll();
    }

    public function findByIdService(int $idFavorito): Favorito
    {
        error_log("FavoritoService::findByIdService()");

        $favorito = $this->favoritoDAO->findById($idFavorito);

        if(!$favorito){
            throw new ErrorResponse(
                404, 
                "Favorito não encontrado", 
                [
                    "message" => 
                        "Não existe favorito com id {$idFavorito}"
                ]
            );
        }

        return $favorito;
    }

    public function updateService(int $idFavorito, array $requestBody): bool
    {
        error_log("FavoritoService::updateService()");

        $favoritoExiste = $this->favoritoDAO->findById($idFavorito);

        if(!$favoritoExiste){
            throw new ErrorResponse(
                404,
                "Favorito não encontrado",
                [
                    "message"=>
                        "Não existe favorito com id {$idFavorito}"
                ]
            );
        }

        $jsonFavorito=$requestBody['favorito'];

        $usuario=$this->usuarioDAO->findById($jsonFavorito['usuario']['idUsuario']);

        if(!$usuario){
            throw new ErrorResponse(
                404,
                "Usuário não encontrado",
                [
                    "message"=>
                        "Usuário informado não existe"
                ]
            );
        }

        $poema=$this->poemaDAO->findById($jsonFavorito['poema']['idPoema']);

        if(!$poema){
            throw new ErrorResponse(
                404,
                "Poema não encontrado",
                [
                    "message"=>
                        "Poema informado não existe"
                ]
            );
        }

        $favorito = new Favorito();
        $favorito->setIdFavorito($idFavorito);
        $favorito->setUsuario($usuario);
        $favorito->setPoema($poema);
        $favorito->setDataFavoritado($jsonFavorito['dataFavoritado']);

        return $this->favoritoDAO->update($favorito);
    }

    public function deleteService(int $idFavorito): bool
    {
        error_log("FavoritoService::deleteService()");

        $favoritoExiste=$this->favoritoDAO->findById($idFavorito);

        if(!$favoritoExiste){
            throw new ErrorResponse(
                404,
                "Favorito não encontrado",
                [
                    "message"=>
                        "Não existe favorito com id {$idFavorito}"
                ]
            );
        }

        $favorito=new Favorito();
        $favorito->setIdFavorito($idFavorito);

        return $this->favoritoDAO->delete($favorito);
    }

    public function countService(): int
    {
        error_log("FavoritoService::countService()");
        return $this->favoritoDAO->count();
    }
}