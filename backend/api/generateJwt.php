<?php
  function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
  }

  function base64url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
  }

  function generate_jwt() {
    //Google's Documentation of Creating a JWT: https://developers.google.com/identity/protocols/OAuth2ServiceAccount#authorizingrequests
    //{Base64url encoded JSON header}
    $jwtHeader = base64url_encode(json_encode(array("alg" => "RS512", "typ" => "JWT")));
    //{Base64url encoded JSON claim set}
    $jwtClaim = base64url_encode(json_encode(["user" => $_SERVER['PHP_AUTH_USER'], "exp" => time() + 60*60*8]));
    //The base string for the signature: {Base64url encoded JSON header}.{Base64url encoded JSON claim set}
    $private_key = "-----BEGIN PRIVATE KEY-----\nyyy\n-----END PRIVATE KEY-----";
    openssl_sign($jwtHeader.".".$jwtClaim, $jwtSig, $private_key, OPENSSL_ALGO_SHA512);
    $jwtSign = base64url_encode($jwtSig);

    //{Base64url encoded JSON header}.{Base64url encoded JSON claim set}.{Base64url encoded signature}
    $jwtAssertion = $jwtHeader.".".$jwtClaim.".".$jwtSign;

    return $jwtAssertion;
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