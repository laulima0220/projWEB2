<?php
namespace Api\DAO;

use Api\Models\Categoria;
use Api\Database\MysqlDatabase;
use Exception;

class CategoriaDAO
{
    private MysqlDatabase $database;

    public function __construct(MysqlDatabase $databaseInstance)
    {
        $this->database = $databaseInstance;
        error_log("CategoriaDAO::__construct()");
    }

    public function create(Categoria $objCategoria): Categoria
    {
        error_log("CategoriaDAO::create()");

        $sql = "
            INSERT INTO categoria (nomeCategoria, descricao)
            VALUES (:nomeCategoria, :descricao)
        ";

        $parametros = [
            ':nomeCategoria' => $objCategoria->getNomeCategoria(),
            ':descricao' => $objCategoria->getDescricao() 
        ];

        $stmt = $this->database->getConnection()->prepare($sql);

        if (!$stmt->execute($parametros)) {
            throw new Exception("Erro ao cadastrar categoria.");
        }

        $novoID = (int) $this->database->getConnection()->lastInsertId();
        $objCategoria->setIdCategoria($novoID);
        return $objCategoria;
    }

    public function delete(Categoria $objCategoria): bool  
    {
        error_log("CategoriaDAO::delete()");

        $sql = "
            DELETE FROM categoria
            WHERE idCategoria = :idCategoria
        ";

        $parametros = [                                        
            ':idCategoria' => $objCategoria->getIdCategoria() 
        ];

        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    public function update(Categoria $objCategoria): bool
    {
        error_log("CategoriaDAO::update()");

        $sql = "
            UPDATE categoria
            SET nomeCategoria = :nomeCategoria,
                descricao = :descricao
            WHERE idCategoria = :idCategoria
        ";

        $parametros = [
            ':nomeCategoria' => $objCategoria->getNomeCategoria(),
            ':descricao' => $objCategoria->getDescricao(),
            ':idCategoria' => $objCategoria->getIdCategoria()
        ];

        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute($parametros);

        return $stmt->rowCount() > 0;
    }

    public function findAll(): array
    {
        error_log("CategoriaDAO::findAll()");

        $sql = "SELECT * FROM categoria";

        $stmt = $this->database->getConnection()->query($sql);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $categorias = [];

        foreach ($matrizArrays as $linhaMatriz) {
            $categoria = new Categoria();

            $categoria->setIdCategoria((int) $linhaMatriz['idCategoria']);
            $categoria->setNomeCategoria($linhaMatriz['nomeCategoria']);
            $categoria->setDescricao($linhaMatriz['descricao']); 

            $categorias[] = $categoria; 
        }

        return $categorias;
    }

    public function count(): int
    {
        error_log("CategoriaDAO::count()");

        $sql = "SELECT COUNT(*) AS qtd FROM categoria";

        $stmt = $this->database->getConnection()->query($sql);

        $linhaMatriz = $stmt->fetch(\PDO::FETCH_ASSOC);

        return (int) $linhaMatriz['qtd'];
    }

    public function findById(int $idCategoria): ?Categoria
    {
        error_log("CategoriaDAO::findById()");

        $resultado = $this->findByField('idCategoria', $idCategoria);

        if (!empty($resultado)) {
            return $resultado[0];
        }

        return null;
    }

    public function findByField(string $field, $value): array
    {
        error_log("CategoriaDAO::findByField()");

        $camposPermitidos = [
            'idCategoria',
            'nomeCategoria',
            'descricao' 
        ];

        if (!in_array($field, $camposPermitidos)) {
            throw new Exception("Campo inválido.");
        }

        $sql = "SELECT * FROM categoria WHERE $field = :value";

        $stmt = $this->database->getConnection()->prepare($sql);

        $stmt->execute([':value' => $value]);

        $matrizArrays = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $categorias = [];

        foreach ($matrizArrays as $linhaMatriz) {
            $categoria = new Categoria();

            $categoria->setIdCategoria((int) $linhaMatriz['idCategoria']);
            $categoria->setNomeCategoria($linhaMatriz['nomeCategoria']);
            $categoria->setDescricao($linhaMatriz['descricao']); 
            
            $categorias[] = $categoria;
        }

        return $categorias;
    }
}