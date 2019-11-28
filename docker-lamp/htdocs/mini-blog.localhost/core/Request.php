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

    // URLにフロントコントローラーが含まれる場合 strposは第一引数が第二引数に出現する位置を探す関数
    if (0 === strpos($request_uri, $script_name)) {
      // SCRIPT_NAMEがベースURLと一致するためそのまま返す
      return $script_name;
    // dirname関数はファイルのパスからディレクトリ部分を抜き出す
    } else if (0 === strpos($request_uri,dirname($script_name))) {
      // rtrimで右側に/が続かないようにする
      return rtrim($script_name, '/');
    }

    return '';
  }

  public function getPathInfo()
  {
    $base_url = $this->getBaseUrl();
    $request_uri = $this->getRequestUri();

    // $posは?が出現するまでの位置
    if (false !== ($pos = strpos($request_uri, '?'))) {
      // substr関数は第一引数で指定した文字列のうち、第二引数で指定した位置から第三引数で指定した文字数分取得する関数
      // ?より前の箇所を抜き出す
      $request_uri = substr($request_uri, 0, $pos);
    }

    // ?以降（GETパラメーター)を除いたrequest_uriからベースURLを引いたものをpath_infoにする
    $path_info = (string)substr($request_uri, strlen($base_url));

    return $path_info;
  }
}
