<?php
namespace Api\Services;

use Api\DAO\PoemaDAO;
use Api\DAO\AutorDAO;
use Api\DAO\CategoriaDAO;
use Api\Models\Poema;
use Api\Models\Autor;
use Api\Models\Categoria;
use Api\Http\MeuTokenJWT;
use Api\Http\ErrorResponse;
use stdClass;

class PoemaService
{
    private PoemaDAO $poemaDAO;
    private AutorDAO $autorDAO;
    private CategoriaDAO $categoriaDAO;

    public function __construct(
        PoemaDAO $poemaDAODependency,
        AutorDAO $autorDAODependency,
        CategoriaDAO $categoriaDAODependency
    ){
        error_log("PoemaService::__construct()");

        $this->poemaDAO=$poemaDAODependency;
        $this->autorDAO=$autorDAODependency;
        $this->categoriaDAO=$categoriaDAODependency;
    }

    public function createService(stdClass $jsonPoema): Poema
    {
        error_log("PoemaService::createService()");

        $autor=new Autor();
        $autor->setIdAutor($jsonPoema->poema->autor->idAutor);

        $autorExiste=$this->autorDAO->findById($autor->getIdAutor());

        if(!$autorExiste){
            throw new ErrorResponse(
                404,
                "Autor não encontrado",
                [
                    "message" =>
                        "Não existe autor com id {$autor->getIdAutor()}"
                ]
            );
        }

        $categoria=new Categoria();
        $categoria->setIdCategoria($jsonPoema->poema->categoria->idCategoria);

        $categoriaExiste=$this->categoriaDAO->findById($categoria->getIdCategoria());

        if(!$categoriaExiste){
            throw new ErrorResponse(
                404,
                "Categoria não encontrada",
                [
                    "message"=>
                        "Não existe categoria com id {$categoria->getIdCategoria()}"
                ]
            );
        }

        $duplicados = $this->poemaDAO->findByField('titulo', $jsonPoema->poema->titulo);
        foreach($duplicados as $p){
            if($p->getAutor()->getIdAutor() === $autorExiste->getIdAutor()){
                throw new ErrorResponse(
                    400,
                    "Poema já cadastrado",
                    [
                        "message" =>
                            "O autor já possui um poema com o título '{$jsonPoema->poema->titulo}'."
                    ]
                );
            }
        }

        $poema=new Poema();
        $poema->setTitulo($jsonPoema->poema->titulo);
        $poema->setConteudo($jsonPoema->poema->conteudo);
        $poema->setAnoPublicacao($jsonPoema->poema->anoPublicacao);
        $poema->setAutor($autorExiste);
        $poema->setCategoria($categoriaExiste);
        
        return $this->poemaDAO->create($poema);
    }

    public function findAll(): array
    {
        error_log("PoemaService::findAll()");
        return $this->poemaDAO->findAll();
    }

    public function findByIdService(int $idPoema): Poema
    {
        error_log("PoemaService::findByIdService()");

        $poema=$this->poemaDAO->findById($idPoema);

        if(!$poema){
            throw new ErrorResponse(
                404,
                "Poema não encontrado",
                [
                    "message"=>
                        "Não existe poema com id {$idPoema}"
                ]
            );
        }

        return $poema;
    }

    public function updateService(int $idPoema, array $requestBody): bool
    {
        error_log("PoemaService::updateService()");

        $poemaExiste=$this->poemaDAO->findById($idPoema);

        if(!$poemaExiste){
            throw new ErrorResponse(
                404,
                "Poema não encontrado",
                [
                    "message"=>
                        "Não existe poema com id {$idPoema}"
                ]
            );
        }

        $jsonPoema=$requestBody['poema'];

        $autor=$this->autorDAO->findById($jsonPoema['autor']['idAutor']);

        if(!$autor){
            throw new ErrorResponse(
                404,
                "Autor não encontrado",
                [
                    "message" =>
                        "Autor informado não existe"
                ]
            );
        }

        $categoria=$this->categoriaDAO->findById($jsonPoema['categoria']['idCategoria']);

        if(!$categoria){
            throw new ErrorResponse(
                404,
                "Categoria não encontrada",
                [
                    "message" =>
                        "Categoria informada não existe"
                ]
            );
        }

        $duplicados = $this->poemaDAO->findByField('titulo', $jsonPoema['titulo']);
        foreach($duplicados as $p){
            if(
                $p->getAutor()->getIdAutor() === $autor->getIdAutor() &&
                $p->getIdPoema() !== $idPoema
            ){
                throw new ErrorResponse(
                    400, 
                    "Título já utilizado", 
                    [
                    "message" => 
                        "O autor já possui outro poema com o título '{$jsonPoema['titulo']}'."
                    ]
                );
            }
        }
        $poema = new Poema();
        $poema->setIdPoema($idPoema);
        $poema->setTitulo($jsonPoema['titulo']);
        $poema->setConteudo($jsonPoema['conteudo']);
        $poema->setAnoPublicacao($jsonPoema['anoPublicacao']);
        $poema->setAutor($autor);
        $poema->setCategoria($categoria);

        return $this->poemaDAO->update($poema);
    }

    public function deleteService(int $idPoema): bool
    {
        error_log("PoemaService::deleteService()");

        $poemaExiste=$this->poemaDAO->findById($idPoema);

        if(!$poemaExiste){
            throw new ErrorResponse(
                404,
                "Poema não encontrado",
                [
                    "message"=>
                        "Não existe poema com id {$idPoema}"
                ]
            );
        }

        $poema = new Poema();
        $poema->setIdPoema($idPoema);

        return $this->poemaDAO->delete($poema);
    }

    public function countService(): int
    {
        error_log("PoemaService::countService()");
        return $this->poemaDAO->count();
    } 
}