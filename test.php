<?php 
 if(exec('echo EXEC') == 'EXEC'){
     echo 'exec est&aacute; habilitado <br><br>';
 }
 echo "<b>Fulpath:</b> ". getcwd() . "<br><br>";

 echo "<b>Ip del servidor: </b>".$_SERVER[SERVER_ADDR];
?>
