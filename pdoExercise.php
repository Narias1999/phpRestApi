<?php
function getConnection(){
  $host = 'localhost';
  $dbname = 'api_db';
  $user = 'root';
  $password = 'narias';
  try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $db->exec('set names utf8');
  } catch(PDOexception $e){
    echo 'error en la conexion: ' . $e;
  }
  return $db;
}
$conn = getConnection();
// var_dump($conn);
$query = "DELETE FROM usuarios WHERE id=1";
$sentence = $conn->prepare($query);
if ($sentence->execute()){
  echo json_encode($sentence->fetchAll(PDO::FETCH_ASSOC));
}