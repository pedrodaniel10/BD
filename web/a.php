<html>
  <body>
<?php
  try{
    include 'config.php';

    	echo("<p>Connected to Postgres.</p>");
  }
  catch (PDOException $e){
    echo("<p>ERROR: {$e->getMessage()}</p>");
  }
?>
  </body>
</html>
