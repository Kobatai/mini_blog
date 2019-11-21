## DBmanagerの接続の仕方

$db_manager = new DbManager();  
$db_manager->connect('master', array(  
  'dsn' => 'mysql:dbname=mydb;host=localhost',  
  'user' => 'myuser',  
  'password' => 'mypass',  
));  
$db_manager->getConnection('master');  
$db_manager->getConnection(); #=> masterがかえってくる  
