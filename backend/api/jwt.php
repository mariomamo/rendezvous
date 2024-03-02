<?php
  function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }

  function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
  }

  function getPayload($jwt) {
    $tokenParts = explode('.', $jwt);
    return json_decode(base64url_decode($tokenParts[1]), true);
  }

  function is_jwt_valid($jwt) {
    $tokenParts = explode('.', $jwt);
    $header = $tokenParts[0];
    $payload = $tokenParts[1];
    $signature = base64url_decode($tokenParts[2]);

    $public_key = "-----BEGIN PUBLIC KEY-----\nxxx\n-----END PUBLIC KEY-----";
    $valid = openssl_verify($header.".".$payload, $signature, $public_key, OPENSSL_ALGO_SHA512);
    if ($valid == 1) {
      $p = json_decode(base64url_decode($payload), true);
      if (time() > $p["exp"]) {
        return 0;
      }
    }
     if ($valid == 0) {
      // echo "error: ".openssl_error_string();
    }
    return $valid;
  }
?>