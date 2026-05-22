<?php
namespace Api\Services;

use Api\Models\Autor;
use Api\DAO\AutorDAO;
use Api\Http\ErrorResponse;
use stdClass;

class AutorService
{
    private AutorDAO $autorDAO;

    public function __construct(AutorDAO $autorDAODependency)
    {
        error_log("AutorService::__construct()");
        $this->autorDAO=$autorDAODependency;
    }

    public function createService(stdClass $objPHP): Autor
    {
        error_log("AutorService::createService()");

        $autor = new Autor();
        $autor->setNomeAutor($objPHP->autor->nomeAutor);
        if(isset($objPHP->autor->nacionalidade))
            $autor->setNacionalidade($objPHP->autor->nacionalidade);
        if(isset($objPHP->autor->biografia))
            $autor->setBiografia($objPHP->autor->biografia);

        $result = $this->autorDAO->findByField(
            'nomeAutor',
            $autor->getNomeAutor()
        );

        if(count($result) > 0){
            throw new ErrorResponse(
                400,
                'Autor já existe',
                [
                    "message" => "O autor {$autor->getNomeAutor()} já existe."
                ]
            );
        }

        return $this->autorDAO->create($autor);
    }

    public function countService(): int
    {
        error_log("AutorService::countService()");
        return $this->autorDAO->count();
    }

    public function findAllService(): array
    {
        error_log("AutorService::findAllService()");
        return $this->autorDAO->findAll();
    }

    public function findByIdService(int $idAutor): ?Autor
    {
        error_log("AutorService::findByIdService()");

        $autorExistente = $this->autorDAO->findById($idAutor);

        if(!$autorExistente){
            throw new ErrorResponse(
                404,
                "Autor não encontrado",
                [
                    "message" => "Não existe autor com id {$idAutor}"
                ]
            );
        }

        return $autorExistente;
    }

    public function updateService(int $idAutor, string $nomeAutor, ?string $nacionalidade, ?string $biografia): bool
    {
        error_log("AutorService::updateService()");

        $autorExistente = $this->autorDAO->findById($idAutor);

        if(!$autorExistente){
            throw new ErrorResponse(
                404,
                "Autor não encontrado",
                [
                    "message" => "Não existe autor com id {$idAutor}"
                ]
            );
        }

        $result = $this->autorDAO->findByField('nomeAutor', $nomeAutor);

        if(count($result) > 0 && $result[0]->getIdAutor() !== $idAutor){
            throw new ErrorResponse(
                400,
                'Autor já existe',
                [
                    "message" => "Já existe outro autor com o nome {$nomeAutor}."
                ]
            );
        }

        $autor = new Autor();
        $autor->setIdAutor($idAutor);
        $autor->setNomeAutor($nomeAutor);
        if($nacionalidade !== null) $autor->setNacionalidade($nacionalidade);
        if($biografia !== null) $autor->setBiografia($biografia);

        return $this->autorDAO->update($autor);
    }

    public function deleteService(int $idAutor): bool
    {
        error_log("AutorService::deleteService()");

        $autorExistente = $this->autorDAO->findById($idAutor);

        if(!$autorExistente){
            throw new ErrorResponse(
                404,
                "Autor não encontrado",
                [
                    "message" => "Não existe autor com id {$idAutor}"
                ]
            );
        }

        $autor = new Autor();
        $autor->setIdAutor($idAutor);

        return $this->autorDAO->delete($autor);
    }
}