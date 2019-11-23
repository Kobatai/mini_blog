<?php

abstract class Controller
{
  protected $controller_name;
  protected $action_name;
  protected $application;
  protected $request;
  protected $response;
  protected $session;
  protected $db_manager;

  public function __construct($application)
  {
    // "Controller"が10文字なので後ろの10文字分取り除いて小文字にする
    // UserControllerならばuserがコントローラー名になる
    // ルーティングに合わせて小文字化
    $this->controller_name = strtolower(substr(get_class($this),0,-10));

    // インスタンス先のプロパティをコントローラークラスにも設定
    $this->application = $application;
    $this->request     = $application->getRequest();
    $this->response    = $application->getResponse();
    $this->session     = $application->getSession();
    $this->db_manager  = $application->getDbManager();
  }

  public function run($action, $params = array())
  {
    $this->action_name = $action;

    $action_method = $action . 'Action';
    // 受け取ったアクション名[アクション名 + Action()]がなければ404エラー画面に飛ばす
    if (!method_exists($this, $action_method)) {
      $this->forward404();
    }

    $content = $this->$action_method($params);

    return $content;
  }

  protected function render($variables = array(), $template = null, $layout = 'layout')
  {
    // viewインスタンス作成する際に渡すdefaultsを設定
    $defaults = array(
      'request'  => $this->request,
      'base_url' => $this->request->getBaseUrl(),
      'session'  => $this->session,
    );

    $view = new View($this->application->getViewDir(), $defaults);


    if (is_null($template)) {
      $template = $this->action_name;
    }

    $path = $this->controller_name .'/' .$template;

    return $view->render($path, $variables, $layout);
  }

  protected function forward404()
  {
    throw new HttpNotFoundException('Forwarded 404 page from ' . $this->controller_name . '/' . $this->action_name);
  }

  protected function redirect($url)
  {
    if (!preg_match('#https?://#', $url)) {
      $protocol = $this->request->isSsl() ? 'https://' : 'http://';
      $host = $this->request->getHost();
      $base_url = $this->request->getBaseUrl();

      $url = $protocol . $host . $base_url .$url;
    }

    $this->response->setStatusCode(302, 'Found');
    $this->response->setHttpHeader('Location', $url);
  }

  // csrf対策
  // ワンタイムトークン作るためのメソッド formでhiddenで送る
  protected function generateCsrfToken($form_name)
  {
    // tokenをフォームごとに識別
    $key = 'csrf_tokens/' . $form_name;
    $tokens = $this->session->get($key, array());
    if (count($tokens) >= 10) {
      // 10個以上トークンがあればarray_shiftで古いものを消す
      array_shift($tokens);
    }


    $token = sha1($form_name . session_id() . microtime());
    $tokens[] = $token;

    // セッションにtokenをセット
    // formから送られたものと識別をする
    $this->session->set($key, $tokens);

    return $token;
  }

  protected function checkCsrfToken($form_name, $token)
  {
    $key = 'csrf_tokens/' . $form_name;
    $tokens = $this->session->get($key, array());

    // tokensの中にPOSTされたtokenがあればtrue
    if ( false !== ($pos = array_search($token, $tokens, true))){

      // 一度チェックされたtokenは破棄
      unset($token[$pos]);

      $this->session->set($key, $tokens);

      return true;
    }

    return false;
  }
}
