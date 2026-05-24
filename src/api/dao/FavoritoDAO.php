<?php
namespace Api\DAO;
use Api\Models\Favorito;
use Api\Models\Usuario;
use Api\Models\Poema;
use Api\Database\MysqlDatabase;           
use Exception;                           
use PDO;

class FavoritoDAO
{
    private MysqlDatabase $database;

    public function __construct(MysqlDatabase $databaseInstance)
    {
        error_log("FavoritoDAO::__construct()");
        $this->database=$databaseInstance;
    }

    public function create(Favorito $favorito): Favorito
    {
        error_log("FavoritoDAO::create()");

        $sql="
            INSERT INTO favorito
            (Usuario_idUsuario, Poema_idPoema, dataFavoritado)
            VALUES (?, ?, ?)
        ";

        $params=[
            $favorito->getUsuario()->getIdUsuario(),
            $favorito->getPoema()->getIdPoema(),
            $favorito->getDataFavoritado(),
        ];

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $insertId = $pdo->lastInsertId();

        if (!$insertId) {
            throw new Exception("Falha ao inserir favorito.");
        }

        return (int) $insertId;
    }

    public function delete(Favorito $favorito): bool
    {
        error_log("FavoritoDAO::delete()");

        $sql="DELETE FROM favorito WHERE idFavorito = ?";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$favorito->getIdFavorito()]);

        return $stmt->rowCount() > 0;
    }

    public function update(Favorito $favorito): bool
    {
        error_log("FavoritoDAO::update()");

        $sql="
            UPDATE favorito
            SET Usuario_idUsuario=?, Poema_idPoema=?, dataFavoritado=?
            WHERE idFavorito=?
        ";

        $params=[
            $favorito->getUsuario()->getIdUsuario(),
            $favorito->getPoema()->getIdPoema(),
            $favorito->getDataFavoritado(),
            $favorito->getIdFavorito(),
        ];

        $pdo = $this->database->getConnection(); 
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public function findAll(): array
    {
        error_log("FavoritoDAO::findAll()");

        $sql="
            SELECT idFavorito, dataFavoritado,
                idUsuario, nomeUsuario,
                idPoema, titulo
            FROM favorito
            JOIN usuario ON favorito.Usuario_idUsuario = idUsuario
            JOIN poema ON favorito.Poema_idPoema = idPoema
        ";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result=[];
        foreach($rows as $row){

            $usuario=new Usuario();
            $usuario->setIdUsuario((int) $row['idUsuario']);
            $usuario->setNomeUsuario($row['nomeUsuario']);

            $poema=new Poema();
            $poema->setIdPoema((int) $row['idPoema']);
            $poema->setTitulo($row['titulo']);

            $favorito=new Favorito();
            $favorito->setIdFavorito((int) $row['idFavorito']);
            $favorito->setUsuario($usuario);
            $favorito->setPoema($poema);
            $favorito->setDataFavoritado($row['dataFavoritado']);

            $result[]=$favorito;
        }

        return $result;
    }

    public function count(): int
    {
        error_log("FavoritoDAO::count()");

        $sql="SELECT COUNT(*) AS qtd FROM favorito";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $row['qtd'];
    }

    public function findById(int $idFavorito): ?Favorito
    {
        $result=$this->findByField('idFavorito', $idFavorito);
        return $result[0] ?? null;
    }

    public function findByField(string $field, $value): array
    {
        error_log("FavoritoDAO::findByField() - Campo: $field, Valor: $value");

        $allowedfields=[
            'idFavorito',
            'Usuario_idUsuario',
            'Poema_idPoema',
            'dataFavoritado'
        ];

        if (!in_array($field, $allowedfields)) {
            throw new Exception("Campo inválido.");
        }
        
        $sql = "SELECT * FROM favorito WHERE $field = :value";

        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([':value' => $value]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $result=[];
        foreach($rows as $row){

            $usuario=new Usuario();
            $usuario->setIdUsuario((int) $row['Usuario_idUsuario']);

            $poema=new Poema();
            $poema->setIdPoema((int) $row['Poema_idPoema']);

            $favorito=new Favorito();
            $favorito->setIdFavorito((int) $row['idFavorito']);
            $favorito->setUsuario($usuario);
            $favorito->setPoema($poema);
            $favorito->setDataFavoritado($row['dataFavoritado']);

            $result[]=$favorito;
        }

        return $result;
    }

        public function findByUsuarioEPoema(int $idUsuario, int $idPoema): ?Favorito
    {
        error_log("FavoritoDAO::findByUsuarioEPoema()");

        $sql = "
            SELECT * FROM favorito 
            WHERE Usuario_idUsuario = ? 
            AND Poema_idPoema = ?
        ";

        $stmt = $this->database->getConnection()->prepare($sql);
        $stmt->execute([$idUsuario, $idPoema]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row) return null;

        $usuario = new Usuario();
        $usuario->setIdUsuario((int) $row['Usuario_idUsuario']);

        $poema = new Poema();
        $poema->setIdPoema((int) $row['Poema_idPoema']);

        $favorito = new Favorito();
        $favorito->setIdFavorito((int) $row['idFavorito']);
        $favorito->setUsuario($usuario);
        $favorito->setPoema($poema);
        $favorito->setDataFavoritado($row['dataFavoritado']);

        return $favorito;
    }
}
