<?php
class DataBase {
	 /* параметры подключения к базе */
	private $host = "localhost";
	private $db_name = "url";
	private $username = "root";
	private $password = "root";
	public $conn;

	private static $db = null; // Единственный экземпляр класса, чтобы не создавать множество подключений

  /* Получение экземпляра класса. Если он уже существует, то возвращается, если его не было, то создаётся и возвращается (паттерн Singleton) */
	public static function getDB() {
		if (self::$db == null) self::$db = new DataBase();
		return self::$db;
	}

  /* private-конструктор, подключающийся к базе данных, устанавливающий локаль и кодировку соединения */
	private function __construct() {
	$this->conn = null;

		try {
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			 $sql = "SHOW TABLES LIKE 'shorturl'";
			 $result =$this->conn->query($sql);
			 $tableExists = $result->rowCount();
			
			 if ($tableExists==0){
				  
				   $sql = "create table shorturl (id integer auto_increment primary key, urltoken varchar(50), url varchar(300));";
				   $this->conn->exec($sql);
			 }
		} catch (PDOException $exception) {
			echo "Ошибка соединения: " . $exception->getMessage();
		}

		return $this->conn;
	}
	

  /* генерация токена если вдруг токен сгенерированый на стороне клиента уже есть в базе */

	 function generate_token(){
		   $chars='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		   $mas=str_split($chars);
		   $token='';
			$t=time();
			$result='';
			for($i=0;$i<8;$i++) {
				$token.=$mas[mt_rand(0, sizeof($chars)-1)];
		   }
		   return $token;
		}
		
	 /* добавление короткого и диного url в базу */	
	  function insertTokenUrl($url,$token) {
		$key=True;
		 /* проверяем есть ли такой url в базе */	
		if ($url) {
		  
		  $sql = "SELECT `url` FROM shorturl WHERE `url`=:url";
		  $result = $this->conn -> prepare($sql);
		  $result->execute(array('url' => $url));
		  $result = $result -> fetchAll(PDO::FETCH_ASSOC);
		 /* если есть проверяем нет ли короткого url для даного длиного url в базе */	
		  if ($result){
			$sql = "SELECT `urltoken` FROM `shorturl` WHERE `urltoken`=:urltoken";
			$result = $this->conn -> prepare($sql);
			$result->execute(array('urltoken' => $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/".$token));
			$result = $result -> fetchAll(PDO::FETCH_ASSOC);
		/* если есть такой короткий url в базе то генерируем токен (сначала токены генерируются на стороне клиента)*/				
			if ($result){
				while ($key){
					$token=generate_token();
					$sql = "SELECT `urltoken` FROM `shorturl` WHERE `urltoken`=:urltoken";
					$result = $conn -> prepare($sql);
					$result->execute(array('urltoken' => $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/".$token));
					$result = $result -> fetchAll(PDO::FETCH_ASSOC);	
					if(!result){
						$key=false;
					}
			}
		  }		
		}
		
	  }
	    /* вставка в базу*/	
		 $sql = "INSERT INTO shorturl (urltoken, url) VALUES (:urltoken, :url)";
		 $stmt =$this->conn->prepare($sql);
		 $stmt->bindValue(":urltoken", $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/".$token);
         $stmt->bindValue(":url", $url);
		 $affectedRowsNumber = $stmt->execute();
		 
		 return $url."-".$_SERVER['SERVER_NAME']."/".$token;
	 }
	
	/* возврощаем оригинальный url соответствующий короткому */	
	 function headersrurl($urltoken) {
		$sql = "SELECT * FROM `shorturl` WHERE `urltoken`=:urltoken";
		$result = $this->conn -> prepare($sql);
		$result->execute(array('urltoken' => $urltoken));
		$result = $result -> fetch(PDO::FETCH_ASSOC);	
		if ($result){
			
			return $result["url"];
				
			
		}
		return false;
	}
	
	/* получаем  url из базы */	
	function showurls(){
	  $sql = "SELECT * FROM shorturl";
	  $result = $this->conn->query($sql);
	  return $result;
	}
  
  /* При уничтожении объекта закрывается соединение с базой данных */
   function __destruct() {
    	
    $this->conn = null;
  }
}
?>