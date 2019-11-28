<?php

class MiniBlogApplication extends Application
{
	protected $login_action = array('account','signin');

	// このphp自体がルートディレクトリに置かれているためそこ返す
	public function getRootDir()
	{
		return dirname(_FILE_);
	}

	protected function registerRoutes()
	{
		return array(
		);
	}

	protected function configure()
	{
		$this->db_manager->connect('master', array(
			'dsn'			=> 'mysql:msql_dbname=mini_blog;hos=localhost',
			'user'			=>'root',
			'password' =>'password',
		));
	}
}