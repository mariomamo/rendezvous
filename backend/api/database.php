<?php
  function getConnection() {
    $host = "Localhost";
    $user = "rendezvous";
    $password = "";
    $database = "my_rendezvous";
    //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT)
    $conn = new mysqli($host, $user, $password, $database) or die ("Errore durante la connessione al server");

    return $conn;
  }

  function getUuid($id, $token) {
    $tokenParts = explode('.', $token);
    $payload = json_decode(base64url_decode($tokenParts[1]), true);

    if ($id && $token && $payload["user"] === "mamo") {
      return $id;
    }
    return uniqid();
  }

  function getExp($exp, $token) {
    $tokenParts = explode('.', $token);
    $payload = json_decode(base64url_decode($tokenParts[1]), true);

    if ($exp === 0) {
      if ($token && $payload["user"] === "mamo") {
        return null;
      }
    } else if ($exp === null) {
      return date("Y-m-d H:i:s", time() + 60*60*24);
    }
    return date("Y-m-d H:i:s", time() + $exp);
  }

  function put($data, $token) {
    if (!$data || !$data["data"]) {
      return 0;
    }
    $data["exp"] = getExp($data["exp"], $token);
    if (!$data["one_shot"]) {
      $data["one_shot"] = false;
    }
    if (!$data["auth_code"]) {
      $data["auth_code"] = "";
    }
    $uuid = getUuid($data["id"], $token);

    $connessione = getConnection();
    $stmt = $connessione -> prepare("INSERT INTO `rendezvous` VALUES (?, ?, ?, ?, 'TEXT', ?)");

    $encryptedData = openssl_encrypt($data["data"], "AES-256-CTR", $data["auth_code"]);
    $stmt -> bind_param("ssiss", $uuid, hash("sha512", $data["auth_code"]), $data["one_shot"], $data["exp"], $encryptedData);
    $result = $stmt -> execute();

    $stmt -> close();
    $connessione -> close();

    if ($result) {
      return json_encode(array("uuid" => $uuid));
    }
    return false;
  }

  function searchRendezvous($id) {
    $connessione = getConnection();
    $stmt = $connessione -> prepare("SELECT * FROM `rendezvous` WHERE id = ?");
    $stmt -> bind_param("s", $id);
    $stmt -> execute();

    $result = $stmt -> get_result();

    $stmt -> close();
    $connessione -> close();

    if ($row = $result -> fetch_array(MYSQLI_ASSOC)) {
      if(removeIfExpired($row)) {
        return false;
      }
      $is_auth = (strcmp($row["auth_code"], hash("sha512", "")) !== 0);
      return json_encode(array("auth" => $is_auth, "data_type" => $row["data_type"]));
    } else {
      return false;
    }
  }

  function getRendezvous($data) {
    $connessione = getConnection();
    if (!$data["auth_code"]) {
      $data["auth_code"] = "";
    }
    $stmt = $connessione -> prepare("SELECT * FROM `rendezvous` WHERE id = ? AND auth_code = ?");
    $stmt -> bind_param("ss", $data["id"], hash("sha512", $data["auth_code"]));
    $stmt -> execute();

    $result = $stmt -> get_result();

    $stmt -> close();
    $connessione -> close();

    if ($row = $result -> fetch_array(MYSQLI_ASSOC)) {
      if(removeIfExpired($row)) {
        return false;
      }
      if ($row["one_shot"]) {
        removeRendezvous($row["id"]);
      }

      $decryptedData = openssl_decrypt($row["data"], "AES-256-CTR", $data["auth_code"]);
      return json_encode(array("data" => $decryptedData));
    } else {
      return false;
    }
  }

  function removeRendezvous($id) {
    $connessione = getConnection();
    $stmt = $connessione -> prepare("DELETE FROM `rendezvous` WHERE id = ?");
    $stmt -> bind_param("s", $id);
    $stmt -> execute();
    $stmt -> close();
    $connessione -> close();
  }

  function removeExpiredRendezvous() {
    $connessione = getConnection();
    $stmt = $connessione -> prepare("DELETE FROM rendezvous WHERE NOW() >= exp");
    $stmt -> execute();
    $stmt -> store_result();
    $item_removed = $stmt -> affected_rows;
    $stmt -> close();
    $connessione -> close();
    return $item_removed;
  }

  function removeIfExpired($row) {
    if ($row["exp"] && strtotime($row["exp"]) < time()) {
      removeRendezvous($row["id"]);
      return true;
    }
    return false;
  }
?>