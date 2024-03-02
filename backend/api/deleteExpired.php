<?php
	include("security.php");
  include("jwt.php");
  include("database.php");
  include("cors.php");

  if ( 'DELETE' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: DELETE');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    echo "only allow DELETE";
    exit;
  }

  header('Content-Type: application/json');
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Credentials: true');
  header("Access-Control-Allow-Methods: PUT");
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

  $jwt = getAuthorizationHeader("Authorization");

  if (is_jwt_valid($jwt) == 0) {
    header("HTTP/1.1 401 Unauthorized");
    return;
  }

  if (($NumberOfRemovedElement = removeExpiredRendezvous()) >= 0) {
    header("HTTP/1.1 200 OK");
    echo json_encode(array("message" => "Removed " . $NumberOfRemovedElement . " elements"));
  } else {
    header("HTTP/1.0 500 Internal server error");
  }
?>