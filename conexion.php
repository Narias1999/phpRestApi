<?php 
class Database {
  private $host = 'localhost';
  private $db_name = 'api_db';
  private $user = 'root';
  private $password = 'narias';
  public $conn;

  public function getConnection(){
    $this->conn = null;
    try{
      $this->conn = new PDO('mysql:host='. $this->host.';dbname='.$this->db_name,$this->user, $this->password);
      $this->conn->exec('set names utf8');
    }catch(PDOException $exception){
      echo 'error de conexión ' . $exception->getMessage();
    }
    return $this->conn;
  }
}