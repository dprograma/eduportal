<?php
require 'vendor/autoload.php';

require_once 'ENVLoader.php';

$host = $_ENV['DB_HOST'];
$database = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];

class DB
{

    public $serverName;
    public $userName;
    public $password;
    public $db;

    public $status;
    public $conn;

    public function __construct($serverName, $userName, $password, $db)
    {
        $this->serverName = $serverName;
        $this->userName = $userName;
        $this->password = $password;
        $this->db = $db;
        $this->status = false;

        try {

            $this->conn = new PDO("mysql:host=$this->serverName;dbname=$this->db", $this->userName, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          

        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();

        }

    }

    public function select($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $this->status = true;

            return $stmt;
        } catch (Exception $e) {
            echo $e->getMessage() . $e->getLine();
        }

    }

    public function insert($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $this->status = true;

        } catch (Exception $e) {
            die($e->getMessage());
        }

    }

    public function update($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $this->status = true;

        } catch (Exception $e) {
            die($e->getMessage());
        }

    }
    public function delete($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
        } catch (Exception $e) {
            throw new Exception("Delete query failed: " . $e->getMessage());
        }
    }

    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }

}


$pdo = new DB('localhost', 'root', '', 'eduportal_eduportal');
// $pdo = new DB('$host', '$username', '$password', '$database');
