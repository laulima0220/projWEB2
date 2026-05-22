<?php
namespace Api\Services;

use Api\Models\Categoria;
use Api\DAO\CategoriaDAO;
use Api\Http\ErrorResponse;
use stdClass;

class CategoriaService
{
    private CategoriaDAO $categoriaDAO;

    public function __construct(CategoriaDAO $categoriaDAODependency)
    {
        error_log("CategoriaService::__construct()");
        $this->categoriaDAO = $categoriaDAODependency;
    }

    public function createService(stdClass $objPHP): Categoria
    {
        error_log("CategoriaService::createService()");

        $categoria = new Categoria();
        $categoria->setNomeCategoria($objPHP->categoria->nomeCategoria);
        if(isset($objPHP->categoria->descricao))
            $categoria->setDescricao($objPHP->categoria->descricao);

        $result = $this->categoriaDAO->findByField(
            'nomeCategoria',
            $categoria->getNomeCategoria()
        );

        if(count($result) > 0){
            throw new ErrorResponse(
                400,
                'Categoria já existe',
                [
                    "message" =>
                        "A categoria {$categoria->getNomeCategoria()} já existe."
                ]
            );
        }

        return $this->categoriaDAO->create($categoria);
    }

    public function countService(): int
    {
        error_log("CategoriaService::countService()");
        return $this->categoriaDAO->count();
    }

    public function findAllService(): array
    {
        error_log("CategoriaService::findAllService()");
        return $this->categoriaDAO->findAll();
    }

    public function findByIdService(int $idCategoria): ?Categoria
    {
        error_log("CategoriaService::findByIdService()");

        $categoriaExistente = $this->categoriaDAO->findById($idCategoria);

        if(!$categoriaExistente){
            throw new ErrorResponse(
                404,
                "Categoria não encontrada",
                [
                    "message" => "Não existe categoria com id {$idCategoria}"
                ]
            );
        }

        return $categoriaExistente;
    }

    public function updateService(int $idCategoria, string $nomeCategoria, ?string $descricao): bool
    {
        error_log("CategoriaService::updateService()");

        $categoriaExistente = $this->categoriaDAO->findById($idCategoria);

        if(!$categoriaExistente){
            throw new ErrorResponse(
                404,
                "Categoria não encontrada",
                [
                    "message" => "Não existe categoria com id {$idCategoria}"
                ]
            );
        }

        $result = $this->categoriaDAO->findByField('nomeCategoria', $nomeCategoria);

        if(count($result) > 0 && $result[0]->getIdCategoria() !== $idCategoria){
            throw new ErrorResponse(
                400,
                'Categoria já existe',
                [
                    "message" => "Já existe outra categoria com o nome {$nomeCategoria}."
                ]
            );
        }

        $categoria = new Categoria();
        $categoria->setIdCategoria($idCategoria);
        $categoria->setNomeCategoria($nomeCategoria);
        if($descricao !== null) $categoria->setDescricao($descricao);

        return $this->categoriaDAO->update($categoria);
    }

    public function deleteService(int $idCategoria): bool
    {
        error_log("CategoriaService::deleteService()");

        $categoriaExistente=$this->categoriaDAO->findById($idCategoria);

        if(!$categoriaExistente){
            throw new ErrorResponse(
                404,
                "Categoria não encontrada",
                [
                    "message"=>
                        "Não existe categoria com id {$idCategoria}"
                ]
            );
        }

        $categoria=new Categoria();
        $categoria->setIdCategoria($idCategoria);
        
        return $this->categoriaDAO->delete($categoria);
    }
}