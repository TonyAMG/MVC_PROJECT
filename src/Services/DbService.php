<?php

//Реализован Singleton

namespace Services;


use PDO;

class DbService
{
    private static $instance;
    private $pdo;
    private $config;

    private function __construct()
    {
        $this->config = ConfigService::getInstance();;
        $this->pdo = new PDO(
            $this->config->db_config['dsn'],
            $this->config->db_config['user'],
            $this->config->db_config['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    //интерфейс получения данных из таблицы
    public function query(string $sql, array $params = [], string $fetch_type = PDO::FETCH_ASSOC): ?array
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);
        if (false === $result) {
            return null;
        }
        return $sth->fetchAll($fetch_type);
    }

    //интерфейс добавления данных в таблицу
    public function create(string $sql, array $params = []): bool
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);
        return $result ?  true :  false;
    }

    //интерфейс получения ID последней вставленной строки
    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    //singleton-интерфейс получения экземпляра
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}