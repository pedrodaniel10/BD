<html>
  <body>
<?php
  try{
    error_reporting(E_ALL);
    ini_set('display_errors',1);
    include 'config.php';

    $mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : "";

    if($mode == "home"){
      echo("<h3>Inserir e eliminar produtos</h3>");
      echo("<a href='b.php?mode=insert_form'>Inserir produto</a><br><br>");
      echo("<a href='b.php?mode=remove_form'>Remover produto</a><br><br>");
      echo("<br><br><a href='index.html'>Voltar</a><br><br>");
    }
    elseif($mode == "insert_form"){
      echo("<h3>Inserir produto</h3>");

      echo("<form action=\"b.php?mode=insert_product\" method=\"post\">");
      echo("<p>EAN:<input type=\"text\" name=\"ean\"/>");
      echo("<p>Designa&ccedil;&atilde;o: <input type=\"text\" name=\"design\"/>");

      //categoria drop down
      echo("<p>Categoria: <select name=\"categoria\">");
      $prep = $db->prepare("SELECT nome FROM categoria ORDER BY nome");
      $prep->execute();
      $result = $prep->fetchAll();
      foreach($result as $row) {
          echo("<option value=\"{$row['nome']}\">");
          echo($row['nome']);
          echo("</option>");
      }
      echo("</select></p>");


      //fornecedores primario drop down
      echo("<p>Fornecedor Prim&aacute;rio: <select name=\"forn_primario\">");
      $prep = $db->prepare("SELECT nif, nome FROM fornecedor ORDER BY nome");
      $prep->execute();
      $result = $prep->fetchAll();
      foreach($result as $row) {
          echo("<option value=\"{$row['nif']}\">");
          echo($row['nome']);
          echo("</option>");
      }
      echo("</select></p>");

      //date
      echo("<p>Data(dd/mm/aaaa): <input type=\"text\" name=\"day\" size=\"1\"/>");
      echo("<input type=\"text\" name=\"month\" size=\"1\"/>");
      echo("<input type=\"text\" name=\"year\" size=\"5\"/>");

      //Fornecedores secundarios
      echo("<p><b>Escolha os fornecedores secund&aacute;rios. Tem de escolher pelo menos um.</b></p>");
      if($result != FALSE || $result != []){
        echo("<table border=\"1\" cellspacing=\"5\" style=\"text-align: center\">\n");
        echo("<tr>\n");
        echo("<td><b>NIF</b></td>\n");
        echo("<td><b>Nome</b></td>\n");
        echo("<td><b>Adicionar</b></td>\n");
        echo("</tr>\n");
      }
      foreach($result as $row){
          echo("<tr>\n");
          echo("<td>{$row['nif']}</td>\n");
          echo("<td>{$row['nome']}</td>\n");
          echo("<td><input type=\"checkbox\" name=\"nifs[]\" value=\"{$row['nif']}\"></td>\n");
          echo("</tr>\n");
      }
      echo("</table>\n");

      //buttom insert
      echo("<br><input type=\"submit\" value=\"Inserir\"/><p>");
      echo("</form>");

      //back link
      echo("<br><br><a href='b.php?mode=home'>Voltar</a><br><br>");
    }
    elseif($mode == "insert_product"){
      echo("<h3>Inserir produto</h3>");
      $forn_sec_selected = isset($_POST['nifs']) ? $_POST['nifs'] : [];
      $forn_primario = $_REQUEST['forn_primario'];
      $end = FALSE;

      /*Verifying fornecedor secundario has atleast 1
       *Verifying fornecedor primario != fornecedor secundario*/
      if($forn_sec_selected == []){
        echo("<h5><font color=\"red\">ERRO: Nenhum Fornecedor Secund&aacute;rio foi selecionado. Deve ser selecionado pelo menos um.</font></h5>");
        $end = TRUE;
      }
      else{
        foreach($forn_sec_selected as $nif){
          if($nif == $forn_primario){
            echo("<h5><font color=\"red\">ERRO: Fornecedor Secund&aacute;rio n&atilde;o pode ser o mesmo que o Fornecedor Prim&aacute;rio.</font></h5>");
            $end = TRUE;
          }
        }
      }

      if($end){}
      else{
        //form information
        $ean = isset($_REQUEST['ean']) ? $_REQUEST['ean'] : "";
        $design = isset($_REQUEST['design']) ? $_REQUEST['design'] : "";
        $categoria = $_REQUEST['categoria'];
        //date
        $day = isset($_REQUEST['day']) ? $_REQUEST['day'] : "";
        $month = isset($_REQUEST['month']) ? $_REQUEST['month'] : "";
        $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : "";
        $date = $year . "-" . $month . "-" . $day;

        $prep = $db->prepare("INSERT INTO produto(ean, design, categoria, forn_primario, data)
                              VALUES (?, ?, ?, ?, ?)");

          $prep->bindParam(1,$ean,PDO::PARAM_INT);
          $prep->bindParam(2,$design,PDO::PARAM_STR,120);
          $prep->bindParam(3,$categoria,PDO::PARAM_STR,50);
          $prep->bindParam(4,$forn_primario,PDO::PARAM_INT);
          $prep->bindParam(5,$date,PDO::PARAM_STR,10);

        $db->query("BEGIN");
        //add product
        try{
          $prep->execute();
        }
        catch (PDOException $e){
          switch($e->getCode()){
            case "22P02": //ean not an int
              echo("<h5><font color=\"red\">ERRO: EAN tem de ser um inteiro.</font></h5>");
              break;
            case "22007": //invalid date
            case "22008":
              echo("<h5><font color=\"red\">ERRO: Data inv&aacute;lida.</font></h5>");
              break;
            case "23505": //violates pk_produto
              echo("<h5><font color=\"red\">ERRO: O EAN $ean j&aacute; existe.</font></h5>");
              break;
          }
          //echo("<h5><font color=\"red\">ERRO: {$e->getCode()}:2.</font></h5>");
          $db->query("ROLLBACK");
          $end = TRUE;
          //echo("<p>ERROR: {$e->getMessage()}</p>");
        }

        if($end){}
        else{
          //add forn_sec
          foreach($forn_sec_selected as $nif){
            $prep = $db->prepare("INSERT INTO fornece_sec(nif, ean)
                                  VALUES (?,?)");
            $prep->bindParam(1,$nif,PDO::PARAM_INT);
            $prep->bindParam(2,$ean,PDO::PARAM_INT);
            $prep->execute();
          }

          $db->query("COMMIT");
          echo("<h5><font color=\"green\">Produto com EAN $ean inserido com sucesso.</font></h5>");
        }
      }
      echo("<br><br><a href='b.php?mode=insert_form'>Voltar</a><br><br>");
    }
    elseif($mode == "remove_form"){
      $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "";
      $execute = isset($_REQUEST['execute']) ? $_REQUEST['execute'] : "";

      echo("<h3>Remover produto</h3>");
      echo("<form action=\"b.php?mode=remove_form&type=search\" method=\"post\">");
      echo("<p>EAN: <input type=\"text\" name=\"ean_search\"/>");
      echo("<input type=\"submit\" value=\"Procurar\"/></p></form>");

      if($execute == "yes"){
        $ean = isset($_REQUEST['ean']) ? $_REQUEST['ean'] : "";

        $prep = $db->prepare("DELETE FROM produto WHERE ean = ?");
        $prep->bindParam(1, $ean, PDO::PARAM_INT);
        try{
          $prep->execute();
          echo("<h5><font color=\"green\">Produto com EAN $ean removido com sucesso.</font></h5>");
        }
        catch (PDOException $e){
          echo("<h5><font color=\"red\">ERRO: {$e->getCode()}:1.</font></h5>");
          //echo("<p>ERROR: {$e->getMessage()}</p>");
        }
      }

      if($type == "search"){
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
            echo("<td><a href=\"b.php?mode=remove_form&execute=yes&ean={$row['ean']}\">Remover</a></td>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
        echo("<br><br><a href='b.php?mode=remove_form'>Voltar</a><br><br>");
      }
      else{
        $prep = $db->prepare("SELECT ean, design FROM produto ORDER BY ean");
        $prep->execute();
        $result = $prep->fetchAll();

        if($result != FALSE || $result != []){
          echo("<table border=\"1\" cellspacing=\"5\" style=\"text-align: center\">\n");
          echo("<tr>\n");
          echo("<td><b>EAN</b></td>\n");
          echo("<td><b>Designa&ccedil;&atilde;o</b></td>\n");
          echo("<td><b>Listar</b></td>\n");
          echo("</tr>\n");
        }
        foreach($result as $row){
            echo("<tr>\n");
            echo("<td>{$row['ean']}</td>\n");
            echo("<td>{$row['design']}</td>\n");
            echo("<td><a href=\"b.php?mode=remove_form&execute=yes&ean={$row['ean']}\">Remover</a></td>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
        echo("<br><br><a href='b.php?mode=home'>Voltar</a><br><br>");
      }
    }
  }
  catch (PDOException $e){
    echo("<h5><font color=\"red\">ERRO: {$e->getCode()}:3.</font></h5>");
    //echo("<p>ERROR: {$e->getMessage()}</p>");
  }
?>
  </body>
</html>
