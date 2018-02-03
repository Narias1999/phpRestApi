<?php 
include_once 'conexion.php';
Class User {
   public $response;
   private $conn;
   function __construct($action) {
      $db = new Database();     
      $this->conn = $db->getConnection();
      switch ($action) {
        case 'create':
          if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->create();
          } else $this->badRequest('POST');
          break;
        case 'getAll': 
          if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $this->getAll();
          } else $this->badRequest('GET');
          break;
        case 'deleteById': 
          if ($_SERVER['REQUEST_METHOD'] === 'DELETE'){
            $this->deleteById();
          } else $this->badRequest('DELETE');
          break;
        case 'update': 
          if ($_SERVER['REQUEST_METHOD'] === 'PUT'){
            $this->update();
          } else $this->badRequest('PUT');
          break;
      }
   }
   private function deleteById(){
     $id =  explode('/',$_GET['PATH_INFO'])[2];
     $query = "DELETE FROM usuarios WHERE id=$id";
     $sentence = $this->conn->prepare($query);
     if($sentence->execute()){
       $this->response = array(
         'status' => 200,
         'message' => 'Usuario eliminado con exito');
     } else $this->queryError('Error al eliminar el usuario');
   }
   private function update(){
     $PUT_DATA = json_decode(file_get_contents('php://input'), true);
     $id =  $PUT_DATA['id'];
     $username =  $PUT_DATA['username'];
     $password =  $PUT_DATA['password'];
     $query = 'UPDATE usuarios SET username=:username, password=:password where id=:id';
     $statement = $this->conn->prepare($query);
     $statement->bindParam('username', $username);
     $statement->bindParam('password', $password);
     $statement->bindParam('id', $id);
     if($statement->execute()) {
       $this->response = array(
         'status' => 200,
         'message' => 'Usuario actualizado con éxito');
     } else $this->queryError('Error al actualizar usuario');
   }
   private function getAll(){
    $query = "SELECT * FROM usuarios";
    $statement = $this->conn->prepare($query);
    if ($statement->execute()){
      $this->response = array(
        'status' => 200,
        'users' => $statement->fetchAll(PDO::FETCH_ASSOC)
      );
    } else $this->queryError('Error al traer usuarios');
   }


   private function create(){
    if(isset($_POST['username']) && isset($_POST['password'])){
      $query = "insert into usuarios SET username=:username, password=:password";
      $statement = $this->conn->prepare($query);
      $statement->bindParam(':username', $_POST['username']);
      $statement->bindParam(':password', $_POST['password']);
      if($statement->execute()){
        $this->response = array(
          'status' => 200, 
          'message' => 'Usuario registrado con exito');
      } else $this->queryError('Error al registrar el usuario');
    } else $this->queryError('No se envió el usuario o la contraseña');
   }
   private function badRequest($method){
     $this->response = array(
        'status' => 500,
        'message' => "BAD REQUEST: La petición debe ser $method");
   }
   private function queryError($message){
     $this->response = array(
        'status' => 500,
        'message' => $message);
   }
}