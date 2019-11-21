<?php
class DbManager
{
	// PDOクラスのインスタンスを配列で保持
	protected $connections = array();

	// 複数のDBへの接続情報 Repositoryクラスと接続名の対応を格納
	protected $repository_connection_map();

	public fucntion connect($name, $params)
	{
		// paramsは接続に必要な情報 mergeで中身を入れる キーはnewするときに必ずあることになる
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
			// current関数は配列の内部ポインタが示す場所を取得 配列の先頭の値の要素を指すように初期化する
			// つまりgetConnectionした際に$nameがなければ、最初に作成した接続先にアクセスする
			return current($this->connections);
		}

		return $this->connections[$name];
	}

	public function setRepositoryConnectionsMap($repository_name, $name)
	{
		$this->repository_connection_map[$repository_name] = $name;
	}

	// Repositoryクラスに対応する接続を取得する
	public function getConnectionForRepository($repository_name)
	{
		if  (isset($this->repository_connection_map[$repository_name])) {
			$name = $this->repository_connection_map[$repository_name];
			// Repostiroryクラスでプロパティが設定されているものはそのまま接続
			$con = $this->getConnection($name);
		} else {
			// そうでなければ最初に作成したものを取得する
			$con = $this->getConnection();
		}

		return $con;
	}
}
