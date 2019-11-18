<?php

class Request
{
  public function isPost()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      return true;
    }

    return false;
  }

  public function getGet($namem, $default = null)
  {
    if (isset($_GET[$name])) {
      return $_GET[$name];
    }

    return $default;
  }

  public function getPost($name, $default = null)
  {
    if (isset($_POST[$name])){
      return $_POST[$name];
    }

    return $default;
  }

  public function getHost()
  {
    // HTTPリクエストヘッダに含まれるホストの値を取得
    if (!empty($_SERVER['HTTP_HOST'])) {
      return $_SERVER['HTTP_HOST'];
    }
    // なければApache側に設定されてホスト名を取得
    return $_SERVER['SERVER_NAME'];
  }

  // HTTPSでアクセスされたか判定 その場合には$_SERVER['HTTPS']にonが入る
  public function isSsl()
  {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      return true;
    }

    return false;
  }

  // ホスト部分以降のurlの値 URL制御を行うため
  public function getRequestUri()
  {
    return $_SERVER['REQUEST_URI'];
  }

  public function getBaseUrl()
  {
    $script_name = $_SERVER['SCRIPT_NAME'];

    $request_uri = $this->getRequestUri();

    if (0 === strpos($request_uri, $script_name)) {
      return $script_name;
    } else if (0 === strpos($request_uri,dirname($script_name))) {
      return rtrim($script_name, '/');
    }

    return '';
  }
}
