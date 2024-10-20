<?
require_once('database.php');
// принимаем нащи данные
    $db = DataBase::getDB();
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
	   $url =   trim(strip_tags($_POST['myurl'])); 
	   $token = trim(strip_tags($_POST['mytoken'])); 
	   $db->insertTokenUrl($url,$token); //заносим данные в базу
	} 
	
	if($_GET["url"]){
	    $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	    $url=$db->headersrurl($url); //проверяем есть ли такой url в базе
	    if ($url){
			header('location: '.$url); //если есть то перенапрвляем по оригинальной ссылке
	   }
	} 
	

?>