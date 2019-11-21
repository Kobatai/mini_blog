<?php
class DbManager
{
	// PDOクラスのインスタンスを配列で保持
	protected $connections = array();

	public fucntion connect($name, $params)
	{
		// paramsは接続に必要な情報　mergeで中身を入れる　キーはnewするときに必ずあることになる
		$params = array_merge(array(
			'dsn'       	=> null,
			'user'		 	=> '',
			'password' => '',
			'options' 	=> array(),
		) ,$params);

		$con = new PDO(
			$params['dsn'],
			$params['user'],
			$params['password'],
			$params['options']
		);

		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$this->connections[$name] = $con;
	}

	public function getConnection($name = null)
	{
		if (is_null($name)) {
			// current関数は配列の内部ポインタが示す場所を取得　ここでは配列の先頭の値
			return current($this->connections);
		}

		return $this->connections[$name];
	}
}