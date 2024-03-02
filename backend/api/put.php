<?php
	include("security.php");
  include("jwt.php");
  include("database.php");
  include("cors.php");

  if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    echo "only allow POST";
    exit;
  }

	header('Content-Type: application/json'); 
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Credentials: true');
  header("Access-Control-Allow-Methods: PUT");
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    
	$data = json_decode(file_get_contents('php://input'), true);
  $jwt = getAuthorizationHeader("Authorization");

  if (!is_jwt_valid($jwt)) {
    $jwt = null;
  }
  if ($data = put($data, $jwt)) {
    header("HTTP/1.1 200 OK");
    echo $data;
  } else {
    header("HTTP/1.0 500 Internal Server Error");
  }
?>