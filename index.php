<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once 'controllers/usersController.php';
$route = explode('/', $_GET['PATH_INFO']);
$resource = $route[0];
$action = $route[1];
$response;
switch ($resource) {
  case 'users':
    $instance = new User($action);
    $response = $instance->response;
    break;
}
echo json_encode($response);