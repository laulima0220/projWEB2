<?php
namespace Api\DAO;
use Api\Models\Poema;
use Api\Models\Autor;
use Api\Models\Categoria;
use Api\Database\MysqlDatabase;           
use Exception;                           
use PDO;

class PoemaDAO
{
    private MysqlDatabase $database;

    public function __construct(MysqlDatabase $databaseInstance)
    {
        error_log("PoemaDAO::__construct()");
        $this->database=$databaseInstance;
    }

    public function create(Poema $poema): int
    {
        error_log("PoemaDAO::create()");

        $sql="
            INSERT INTO poema
            (titulo, conteudo, anoPublicacao, Autor_idAutor, Categoria_idCategoria)
            VALUES (?, ?, ?, ?, ?)
        ";

        $params=[
            $poema->getTitulo(),
            $poema->getConteudo(),
            $poema->getAnoPublicacao(),
            $poema->getAutor()->getIdAutor(),
            $poema->getCategoria()->getIdCategoria(),
        ];

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $insertId = $pdo->lastInsertId();

        if (!$insertId) {
            throw new Exception("Falha ao inserir poema.");
        }

        return (int) $insertId;
    }

    public function delete(Poema $poema): bool
    {
        error_log("PoemaDAO::delete()");

        $sql="DELETE FROM poema WHERE idPoema = ?";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$poema->getIdPoema()]);

        return $stmt->rowCount() > 0;
    }

    public function update(Poema $poema): bool
    {
        error_log("PoemaDAO::update()");

        $sql="
            UPDATE poema
            SET titulo=?, conteudo=?, anoPublicacao=?, Autor_idAutor=?, Categoria_idCategoria=?
            WHERE idPoema=?
        ";

        $params = [
            $poema->getTitulo(),
            $poema->getConteudo(),
            $poema->getAnoPublicacao(),
            $poema->getAutor()->getIdAutor(),
            $poema->getCategoria()->getIdCategoria(),
            $poema->getIdPoema(),
        ];

        $pdo = $this->database->getConnection(); 
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public function findAll(): array
    {
        error_log("PoemaDAO::findAll()");

        $sql="
            SELECT idPoema, titulo, conteudo, anoPublicacao,
                idAutor, nomeAutor,
                idCategoria, nomeCategoria
            FROM poema
            JOIN autor ON poema.Autor_idAutor = idAutor
            JOIN categoria ON poema.Categoria_idCategoria = idCategoria
        ";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result=[];
        foreach($rows as $row){

            $autor=new Autor();
            $autor->setIdAutor((int) $row['idAutor']);
            $autor->setNomeAutor($row['nomeAutor']);

            $categoria=new Categoria();
            $categoria->setIdCategoria((int) $row['idCategoria']);
            $categoria->setNomeCategoria($row['nomeCategoria']);

            $poema=new Poema();
            $poema->setIdPoema((int) $row['idPoema']);
            $poema->setTitulo($row['titulo']);
            $poema->setConteudo($row['conteudo']);
            $poema->setAnoPublicacao($row['anoPublicacao']);
            $poema->setAutor($autor);
            $poema->setCategoria($categoria);

            $result[]=$poema;
        }

        return $result;
    }

    public function count(): int
    {
        error_log("PoemaDAO::count()");

        $sql="SELECT COUNT(*) AS qtd FROM poema";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $row['qtd'];
    }

    public function findById(int $idPoema): ?Poema
    {
        $result=$this->findByField('idPoema', $idPoema);
        return $result[0] ?? null;
    }

    public function findByField(string $field, $value): array
    {
        error_log("PoemaDAO::findByField() - Campo: $field, Valor: $value");

        $camposPermitidos=[
            'idPoema',
            'titulo',
            'conteudo',
            'anoPublicacao',
            'Autor_idAutor',
            'Categoria_idCategoria'
        ];
        
        if (!in_array($field, $camposPermitidos)) {
            throw new Exception("Campo inválido.");
        }

        $sql = "SELECT * FROM poema WHERE $field = :value";

        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([':value' => $value]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $result=[];
        foreach($rows as $row){

            $autor=new Autor();
            $autor->setIdAutor((int) $row['idAutor']);

            $categoria=new Categoria();
            $categoria->setIdCategoria((int) $row['idCategoria']);

            $poema=new Poema();
            $poema->setIdPoema((int) $row['idPoema']);
            $poema->setTitulo($row['titulo']);
            $poema->setConteudo($row['conteudo']);
            $poema->setAnoPublicacao($row['anoPublicacao']);
            $poema->setAutor($autor);
            $poema->setCategoria($categoria);

            $result[]=$poema;
        }

        return $result;
    }

}