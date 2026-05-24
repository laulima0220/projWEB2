<?php
namespace Api\DAO;

use Api\Models\Usuario;
use Api\Database\MysqlDatabase;
use Exception;
use PDO;

class UsuarioDAO
{
    private MysqlDatabase $database;

    public function __construct(MysqlDatabase $databaseInstance)
    {
        $this->database = $databaseInstance;
        error_log("UsuarioDAO::__construct()");
    }

    public function create(Usuario $usuario): Usuario
    {
        error_log("UsuarioDAO::create()");

        $senhaHash = password_hash($usuario->getSenha(), PASSWORD_BCRYPT, ['cost' => 12]);

        $sql = "
            INSERT INTO usuario
            (nomeUsuario, email, senha, admin)
            VALUES (?, ?, ?, ?)
        ";

        $params = [
            $usuario->getNomeUsuario(),
            $usuario->getEmail(),
            $senhaHash,
            $usuario->getAdmin()
        ];

        $pdo=$this->database->getConnection();
        $stmt=$pdo->prepare($sql);
        $stmt->execute($params);

        $insertId=$pdo->lastInsertId();

        if(!$insertId){
            throw new Exception("Falha ao inserir usuário");
        }

        $usuario->setIdUsuario((int) $insertId);
        return $usuario;
    }

    public function delete(Usuario $usuario): bool
    {
        error_log("UsuarioDAO::delete()");

        $sql="  DELETE FROM usuario WHERE idUsuario = ?";

        $pdo=$this->database->getConnection();
        $stmt=$pdo->prepare($sql);
        $stmt->execute([$usuario->getIdUsuario()]);

        return $stmt->rowCount()>0;
    }

    public function update(Usuario $usuario): bool
    {
        error_log("UsuarioDAO::update()");

        $pdo=$this->database->getConnection();

        if (!empty($usuario->getSenha())){

            $senhaHash = password_hash($usuario->getSenha(),PASSWORD_BCRYPT, ['cost' => 12]);

            $sql="
                UPDATE usuario
                SET nomeUsuario=?, email=?, senha=?, admin=?
                WHERE idusuario=?
            ";
            $params = [
                $usuario->getNomeUsuario(),
                $usuario->getEmail(),
                $senhaHash,
                $usuario->getAdmin(),
                $usuario->getIdUsuario(),
            ];
        }
        else {
            $sql="
                UPDATE usuario
                SET nomeUsuario=?, email=?, admin=?
                WHERE idUsuario=?
            ";
            $params = [
                $usuario->getNomeUsuario(),
                $usuario->getEmail(),
                $usuario->getAdmin(),
                $usuario->getIdUsuario(),
            ];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public function findAll(): array
    {
        error_log("UsuarioDAO::findAll()");

        $sql="
            SELECT idUsuario, nomeUsuario, email, admin
            FROM usuario
        ";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usuarios = [];

        foreach ($rows as $row) {
            $usuario = new Usuario();

            $usuario->setIdUsuario((int) $row['idUsuario']);
            $usuario->setNomeUsuario($row['nomeUsuario']);
            $usuario->setEmail($row['email']);
            $usuario->setAdmin($row['admin']);

            $usuarios[] = $usuario;
        }

        return $usuarios;
    }

    public function count(): int
    {
        error_log("UsuarioDAO::count()");

        $sql="SELECT COUNT(*) AS qtd FROM usuario";

        $pdo=$this->database->getConnection();
        $stmt = $pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $row['qtd'];
    }

    public function findById(int $idUsuario): ?Usuario
    {
        $result = $this->findByField('idUsuario', $idUsuario);
        return $result[0] ?? null;
    }

    public function findByField(string $field, $value): array
    {
        error_log("UsuarioDAO::findByField() - Campo: $field, Valor: $value");

        $allowedFields = ['idUsuario', 'nomeUsuario', 'email', 'admin'];
        if(!in_array($field, $allowedFields)){
            throw new Exception("Campo inválido para busca.");
        }

        $sql = "SELECT * FROM usuario WHERE $field = ?";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$value]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $row) {
            $usuario = new Usuario();
            $usuario->setIdUsuario((int) $row['idUsuario']);
            $usuario->setNomeUsuario($row['nomeUsuario']);
            $usuario->setEmail($row['email']);
            $usuario->setAdmin((int) $row['admin']);

            $result[] = $usuario;
        }

        return $result;
    }

    public function login(Usuario $usuario): ?Usuario
    {
        error_log("UsuarioDAO::login()");

        $sql="
            SELECT idUsuario, nomeUsuario, email, senha, admin
            FROM usuario
            WHERE email=?
        ";

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario->getEmail()]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC); 

        if (!$row) {
            error_log("Usuário não encontrado.");
            return null;
        }

        if (!password_verify($usuario->getSenha(), $row['senha'])) {
            error_log("Senha inválida.");
            return null;
        }

        $usuario = new Usuario();
        $usuario->setIdUsuario((int) $row['idUsuario']);
        $usuario->setNomeUsuario($row['nomeUsuario']);
        $usuario->setEmail($row['email']);
        $usuario->setAdmin((int) $row['admin']);

        return $usuario;
    }
}