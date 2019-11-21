<?php

abstract class DbRepository
{
  protected $con;

  // PDOクラスのインスタンスに接続する
  public function __construct($con)
  {
    $this->setConnection($con);
  }

  public function setConnection($con)
  {
    $this->con = $con;
  }

  public function exectute($sql, $params = array())
  {
    $stmt = $this->con->prepare($sql);
    $stmt->execute($params);

    return $stmt;
  }

  public function fetch($sql, $params = array())
  {
    // FETCH_ASSOCは発行したSQLで取得した値を連想配列で受け取る指定
    // これをしないと連番の配列で受け取ることになる
    return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
  }

  public function fetchAll($sql, $params = array())
  {
    return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  }
}
