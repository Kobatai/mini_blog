<?php

class Session
{
  protected static $sessionStarted = false;
  protected static $sessionIdRegenerated = false;

  public function __construct()
  {
    if (!self::$sessionStarted) {
      session_start();

      self::$sessionStarted = true;
    }
  }

  public function set($name, $value)
  {
    $_SESSION[$name] = $value;
  }

  public function get($name, $default = null)
  {
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }

    return $default;
  }

  // 特定のセッションを消す
  public function remove($name)
  {
    unset($_SESSION[$name]);
  }

  // セッション全消し
  public function clear()
  {
    $_SESSION = array();
  }

  public function regenerate($destroy = true)
  {
    if (!self::$sessionIdRegenerated) {
      // セッションIDを新しく発行する
      session_regenerate_id($destroy);

      self::$sessionIdRegenerated = true;
    }
  }

  // ユーザーが_authenticatedというセッションのキーでログインしているかどうかのフラグをセットする
  // セッション固定攻撃対策
  public function setAuthenticated($bool)
  {
    $this->set('_authenticated', (bool)$bool);

    // ログインしたらsessionIDをregenerate
    $this->regenerate();
  }

  public function isAuthenticated()
  {
    return $this->get('_authenticated', false);
  }

}
