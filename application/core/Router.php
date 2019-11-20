<?php

class Router
{
	protected $routes;

	// ルーティングの定義配列をコンストラクタのパラメータとして受け取る
	public function __construct($definitions)
	{
		$this->routes = $this->compileRoutes($definitions);
	}

	// '/item/:action'のような動的なURLを正規表現で分割し再度マージする
	public function compileRoutes($definitions)
	{
		$routes = array();

		foreach ($definitions as $url => $params ){
			$tokens = explode('/' , ltrim($url, '/'));
			foreach ($tokens as $i => $token) {
				if (0 === strpos($token, ':')) {
					$name = substr($token ,1);
					// (:P<名前>パターン)」とすると、指定した名前でその値を取得できる
					$token = '(?P<' . $name .'>[^/]+)';
				}

				$tokens[$i] = $token;
			}

			$pattern = '/' . implode('/' , $tokens);
			$routes[$pattern] = $params;
		}

		return $routes;
	}

	// 変換済みのURLを正規表現を用いてルーティングとマッチングさせる
	public function resolove($path_info)
	{
		if ('/' ! == substr($path_info, 0, 1)){
			$path_info = '/' . $path_info;
		}

		foreach ($this->routes as $pattern => $params) {
			if (preg_match('#^' . $pattern . '$#' , $path_info, $matches)) {
				$params = array_merge($params, $matches);

				return $params;
			}
		}

		return false;
	}
}