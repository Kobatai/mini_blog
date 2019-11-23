<?php

class View
{
  protected $base_dir;
  protected $defaults;
  protected $layout_variables = array();

  public function __construct($base_dir, $defaults = array())
  {
    // viewsディレクトリへの絶対パス
    $this->base_dir = $base_dir;
    // 全てのviewファイルに渡す変数のデフォルト
    $this->defaults = $defaults;
  }

  public function setLayoutVar($name, $value)
  {
    $this->layout_variables[$name] = $value;
  }

  // 第二引数のvariablesはビューファイルに渡す連想配列の変数
  // 第三引数はdefaultではfalseにし、コントローラーから呼び出された際にはビューを読み込む
  public function render($_path, $_variables = array(),$_layout = false)
  {
    $_file = $this->base_dir . '/' . $_path .'.php';

    // defalutsの変数と受け取った変数をmerge
    // extractで連想配列からkeyの中にvalueが入った変数への展開を行う
    extract(array_merge($this->defaults, $_variables));

    // アウトプットバッファリング開始 内部に出力情報をバッファリング echoなどで出力した文字列は画面に表示せず溜め込む
    ob_start();
    // obの自動フラッシュ制御
    ob_implicit_flush(0);

    require $_file;

    // バッファされた内容を取得する
    $content = ob_get_clean();

    if ($_layout) {
      $content = $this->render($_layout,
        array_merge($this->layout_variables, array(
          'content' => $content,
        )
      ));
    }

    return $content;
  }

  public function escape($string)
  {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
  }
}
