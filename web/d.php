<html>
  <body>
    <h3>Alterar designa&ccedil;&atilde;o de produtos</h3>
    <form action="d.php" method="post">
      <p><input type="hidden" name="mode" value="search"/></p>
      <p>EAN: <input type="text" name="ean_search"/>
        <input type="submit" value="Procurar"/>
      </p>
    </form>
<?php
  try{
    error_reporting(E_ALL);
    ini_set('display_errors',1);
    include 'config.php';

    $mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : "";
    $ean = isset($_REQUEST['ean']) ? $_REQUEST['ean'] : "";
    if ($mode == "home"){

      $prep = $db->prepare("SELECT ean, design FROM produto ORDER BY ean");
      $prep->execute();
      $result = $prep->fetchAll();

      echo("<table border=\"1\" cellspacing=\"5\" style=\"text-align: center\">\n");
      echo("<tr>\n");
      echo("<td><b>EAN</b></td>\n");
      echo("<td><b>Designa&ccedil;&atilde;o</b></td>\n");
      echo("<td><b>Alterar</b></td>\n");
      echo("</tr>\n");
        foreach($result as $row){
            echo("<tr>\n");
            echo("<td>{$row['ean']}</td>\n");
            echo("<td>{$row['design']}</td>\n");
            echo("<td><a href=\"d.php?mode=show&ean={$row['ean']}\">Alterar</a></td>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
        echo("<br><br><a href='index.html'>Voltar</a><br><br>");
    }
    elseif ($mode == "search"){

      $ean = $_REQUEST['ean_search'];

      $prep = $db->prepare("SELECT ean, design FROM produto WHERE ean = ? ORDER BY ean");
      try{
        $prep->bindParam(1,$ean,PDO::PARAM_INT);
        $prep->execute();
      }
      catch (PDOException $e){
        echo("<h5><font color=\"red\">ERRO: EAN tem de ser um inteiro.</font></h5>");
      }
      $result = $prep->fetchAll();
      if($result != FALSE || $result != []){
        echo("<table border=\"1\" cellspacing=\"5\" style=\"text-align: center\">\n");
        echo("<tr>\n");
        echo("<td><b>EAN</b></td>\n");
        echo("<td><b>Designa&ccedil;&atilde;o</b></td>\n");
        echo("<td><b>Listar</b></td>\n");
        echo("</tr>\n");
      }
      $number_rows = count($result);
      echo("<p>Foram encontrado(s) $number_rows produto(s).</p>");
      foreach($result as $row){
          echo("<tr>\n");
          echo("<td>{$row['ean']}</td>\n");
          echo("<td>{$row['design']}</td>\n");
          echo("<td><a href=\"d.php?mode=show&ean={$row['ean']}\">Alterar</a></td>\n");
          echo("</tr>\n");
      }
        echo("</table>\n");
        echo("<br><br><a href='d.php?mode=home'>Voltar</a><br><br>");
    }
    elseif ($mode == "show"){
        echo("<h3>EAN = $ean</h3>");

        echo("<form action=\"d.php?mode=change&ean=$ean\" method=\"post\">");
        echo("<p><input type=\"hidden\" name=\"mode\" value=\"change\"/></p>");
        echo("<p>Designa&ccedil;&atilde;o: <input type=\"text\" name=\"design\"/>");
        echo("<input type=\"submit\" value=\"Alterar\"/><p></form>");

        echo("<br><br><a href='d.php?mode=home'>Voltar</a><br><br>");
    }
    elseif ($mode == "change"){
      $design = $_REQUEST['design'];

      $prep = $db->prepare("UPDATE produto
                            SET design = ?
                            WHERE ean = ?");
      $prep->bindParam(1, $design, PDO::PARAM_STR, 120);
      $prep->bindParam(2, $ean, PDO::PARAM_INT);
      $prep->execute();
      $result = $prep->fetchAll();

      if($result != FALSE){
        echo("<p>A designa&ccedil;&atilde;o do produto com EAN=$ean foi alterada para: \"$design\".</p>");
      }

      echo("<br><br><a href='d.php?mode=home'>Voltar</a><br><br>");
    }
  }
  catch (PDOException $e){
    echo("<p>ERROR: {$e->getMessage()}</p>");
  }
?>
  </body>
</html>
