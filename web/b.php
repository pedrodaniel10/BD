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
      echo("<p>Designa&ccedil;&atilde;o: <input type=\"text\" name=\"ean\"/>");

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
      $forn_sec_selected = $_POST['nifs'];
      foreach($forn_sec_selected as $nif){
        if($nif == $_REQUEST['forn_primario']){
          echo("<h5><font color=\"red\">ERRO: Fornecedor Secund&aacute;rio n&atilde;o pode ser o mesmo que o Fornecedor Prim&aacute;rio.</font></h5>");
        }
      }

      echo("<br><br><a href='b.php?mode=insert_form'>Voltar</a><br><br>");
    }
  }
  catch (PDOException $e){
    echo("<p>ERROR: {$e->getMessage()}</p>");
  }
?>
  </body>
</html>
