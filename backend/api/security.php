<?php
  function getHeader($header) {
    foreach (getallheaders() as $name => $value) {
      if (strtolower($name) == strtolower($header))
        return $value;
    }
  }

  function getAuthorizationHeader() {
    return substr(getHeader("Authorization"), 7);
  }
?>