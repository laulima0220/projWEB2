<?php

namespace Api\DAO;
use Api\Models\Autor;
use Api\Database\MysqlDatabase;           
use Exception;                            

class AutorDAO
{
    private MysqlDatabase $database;

    public function __construct(MysqlDatabase $databaseInstance)
    {
        $this->database=$databaseInstance;
        error_log("AutorDAO::__construct()");
    }

    public function create(Autor $objAutor): Autor
    {
        error_log("AutorDAO::create()");

        $sql="
            INSERT INTO autor (nomeAutor, nacionalidade, biografia)
            VALUES (:nomeAutor, :nacionalidade, :biografia)
        ";

        $parametros=[
            ':nomeAutor'=> $objAutor->getNomeAutor(),
            ':nacionalidade'=>$objAutor->getNacionalidade(),
            ':biografia'=>$objAutor->getBiografia()
        ];

        $stmt=$this->database->getConnection()->prepare($sql);

        if(!$stmt->execute($parametros)){
            throw new Exception("Erro ao cadastrar autor.");
        }

        $novoID=(int) $this->database->getConnection()->lastInsertId();
        $objAutor->setIdAutor($novoID);
        return $objAutor;
    }

    public function delete(Autor $objAutor): bool
    {
        error_log("AutorDAO::delete()");

        $sql="
            DELETE FROM autor
            WHERE idAutor = :idAutor
        ";

        $parametros = [
            ':idAutor' => $objAutor->getIdAutor()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    public function update(Autor $objAutor): bool
    {
        error_log("AutorDAO::update()");

        $sql="
            UPDATE autor
            SET nomeAutor = :nomeAutor,
                nacionalidade = :nacionalidade,
                biografia = :biografia
            WHERE idAutor = :idAutor
        ";

        $parametros = [
            ':nomeAutor' => $objAutor->getNomeAutor(),
            ':nacionalidade' => $objAutor->getNacionalidade(),
            ':biografia' => $objAutor->getBiografia(),
            ':idAutor' => $objAutor->getIdAutor()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    public function findAll(): array
    {
        error_log("AutorDAO::findAll()");

        $sql="SELECT * FROM autor";

        $stmt=$this->database->getConnection()->query($sql);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $autores = [];

        foreach($matrizArrays as $linhaMatriz){
            $autor = new Autor();

            $autor->setIdAutor((int) $linhaMatriz['idAutor']);
            $autor->setNomeAutor($linhaMatriz['nomeAutor']);
            $autor->setNacionalidade($linhaMatriz['nacionalidade']);
            $autor->setBiografia($linhaMatriz['biografia']);

            $autores[] = $autor;
        }

        return $autores;
    }

    public function count(): int
    {
        error_log("AutorDAO::count()");

        $sql = "SELECT COUNT(*) AS qtd FROM autor";

        $stmt = $this->database->getConnection()->query($sql);

        $linhaMatriz = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) $linhaMatriz['qtd'];
    }

    public function findById(int $idAutor): ?Autor
    {
        error_log("AutorDAO::findById()");

        $resultado = $this->findByField('idAutor', $idAutor);

        if(!empty($resultado)){
            return $resultado[0];
        }

        return null;
    }

    public function findByField(string $field, $value): array
    {
        error_log("AutorDAO::findByField()");

        $camposPermitidos = [
            'idAutor',
            'nomeAutor',
            'nacionalidade',
            'biografia'
        ];

        if (!in_array($field, $camposPermitidos)) {
            throw new Exception("Campo inválido.");
        }

        $sql = "SELECT * FROM autor WHERE $field = :value";

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute([':value' => $value]);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $autores = [];

        foreach($matrizArrays as $linhaMatriz) {
            $autor = new Autor();

            $autor->setIdAutor((int) $linhaMatriz['idAutor']);
            $autor->setNomeAutor($linhaMatriz['nomeAutor']);
            $autor->setNacionalidade($linhaMatriz['nacionalidade']);
            $autor->setBiografia($linhaMatriz['biografia']);
            
            $autores[] = $autor;
        }

        return $autores;
    }
}