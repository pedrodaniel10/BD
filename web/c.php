<html>
  <body>
    <h3>Listar eventos de reposi&ccedil;&atilde;o</h3>
    <form action="c.php" method="post">
      <p><input type="hidden" name="mode" value="search"/></p>
      <p>EAN: <input type="text" name="ean_search"/>
        <input type="submit" value="Procurar"/>
      </p>
    </form>
<?php
  try{
    //error_reporting(E_ALL);
    //ini_set('display_errors',1);
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
      echo("<td><b>Listar</b></td>\n");
      echo("</tr>\n");
        foreach($result as $row){
            echo("<tr>\n");
            echo("<td>{$row['ean']}</td>\n");
            echo("<td>{$row['design']}</td>\n");
            echo("<td><a href=\"c.php?mode=list&ean={$row['ean']}\">Listar reposi&ccedil;&otilde;es</a></td>\n");
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
          echo("<p>ERRO: EAN tem de ser um inteiro.</p>");
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
            echo("<td><a href=\"c.php?mode=list&ean={$row['ean']}\">Listar reposi&ccedil;&otilde;es</a></td>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
        echo("<br><br><a href='c.php?mode=home'>Voltar</a><br><br>");
    }
    elseif ($mode == "list"){

        echo("<h3>EAN = $ean</h3>");
        $prep = $db->prepare("SELECT operador, instante, unidades
                              FROM evento_reposicao NATURAL JOIN reposicao
                              WHERE ean = ?
                              ORDER BY instante");
        $prep->bindParam(1,$ean,PDO::PARAM_INT);
        $prep->execute();
        $result = $prep->fetchAll();

        if($result != FALSE || $result != []){
          echo("<table border=\"1\" cellspacing=\"5\" style=\"text-align: center\">\n");
          echo("<tr>\n");
          echo("<td><b>Operador</b></td>\n");
          echo("<td><b>Instante</b></td>\n");
          echo("<td><b>Unidades</b></td>\n");
          echo("</tr>\n");
        }
        $number_rows = count($result);
        echo("<p>Foram encontrado(s) $number_rows evento(s) de reposi&ccedil&atildeo.</p>");
        foreach($result as $row){
            echo("<tr>\n");
            echo("<td>{$row['operador']}</td>\n");
            echo("<td>{$row['instante']}</td>\n");
            echo("<td>{$row['unidades']}</td>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
        echo("<br><br><a href='c.php?mode=home'>Voltar</a><br><br>");
    }
  }
  catch (PDOException $e){
    echo("<p>ERROR: {$e->getMessage()}</p>");
  }
?>
  </body>
</html>
