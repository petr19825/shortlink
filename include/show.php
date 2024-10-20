<? 
require_once('database.php');
 $db = DataBase::getDB();
 $result=$db->showurls();
?>
<table>
	   <th>исходный URL</th> <th>короткий URL</th> <th>скопировать короткий URL</th>
	   <? 
	   if ($result){
	   foreach($result as $row){
		?>
		<tr>
               <td><?echo $row["url"]?></td> <td><a  href="<?echo $row["urltoken"] ?>"><?echo $row["urltoken"]?></a></td> <td><button onclick='return copy(this)' atrlink="<?echo $row["urltoken"] ?>" class="but">скопировать ссылку</button></td> 
       </tr>
	   <?
         } 
	   }
      ?>
</table>