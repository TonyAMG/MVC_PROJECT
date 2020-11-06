<?php

//Реализован Singleton

namespace Services;


use lib\Config;
use PDO;

class DbService
{
    private static $instance;
    private $pdo;
    private $config;

    private function __construct()
    {
        $this->config = new Config();
        $this->pdo = new PDO(
            $this->config->db_config['dsn'],
            $this->config->db_config['user'],
            $this->config->db_config['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public function query(string $sql, array $params = [], string $className = 'stdClass'): ?array
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);
        if (false === $result) {
            return null;
        }
        return $sth->fetchAll(PDO::FETCH_CLASS, $className);
    }

    public function create(string $sql, array $params = []) : bool
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);
        return $result ?  true :  false;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}