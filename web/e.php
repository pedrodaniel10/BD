<html>
  <body>
    <h3>Listar sub-categorias de uma super-categoria</h3>
    <form action="e.php" method="post">
      <p><input type="hidden" name="mode" value="list"/></p>
      <p>Nome: <input type="text" name="nome"/>
        <input type="submit" value="Procurar"/>
      </p>
    </form>
<?php

  function checkSubCats($categories) {

    $num_cats = 0;
    foreach($categories as $row) {
        $num_cats++;
    }

    foreach($categories as $row) {
        $prep = $db->prepare("SELECT categoria
                              FROM constituida
                              WHERE super_categoria = :nome");
        $prep->bindParam(":nome", $row['categoria']);
        $prep->execute();
        $subcats = $prep->fetchAll();
        $subcats = array_filter($subcats);
        if (!empty($subcats)) {
            //array_merge($resultCats[0], $subcats[0]);
            checkSubCats($subcats);
        }
    }
  }

  try{
    error_reporting(E_ALL);
    ini_set('display_errors',1);
    include 'config.php';

    $mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : "";
    $nome = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : "";
    if ($mode == "home"){

      $prep = $db->prepare("SELECT nome FROM super_categoria");
      $prep->execute();
      $result = $prep->fetchAll();

      echo("<table border=\"1\" cellspacing=\"5\" style=\"text-align: center\">\n");
      echo("<tr>\n");
      echo("<td><b>Nome</b></td>\n");
      echo("<td><b>Listar</b></td>\n");
      echo("</tr>\n");
        foreach($result as $row){
            echo("<tr>\n");
            echo("<td>{$row['nome']}</td>\n");
            echo("<td><a href=\"e.php?mode=list&nome={$row['nome']}\">Listar sub-categorias</a></td>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
        echo("<br><br><a href='index.html'>Voltar</a><br><br>");
    }
    elseif ($mode == "list"){

        echo("<h3>Nome = $nome</h3>");
        $prep = $db->prepare("SELECT categoria
                              FROM constituida
                              WHERE super_categoria = :nome");
        $prep->bindParam(":nome", $nome);
        $prep->execute();
        $resultCats = $prep->fetchAll();

        //checkSubCats($resultCats);

        echo("<table border=\"1\" cellspacing=\"5\" style=\"text-align: center\">\n");
        echo("<tr>\n");
        echo("<td><b>Nome</b></td>\n");
        echo("</tr>\n");
        foreach($resultCats as $row){
            echo("<tr>\n");
            echo("<td>{$row['categoria']}</td>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
        echo("<br><br><a href='e.php?mode=home'>Voltar</a><br><br>");
    }
  }
  catch (PDOException $e){
    echo("<p>ERROR: {$e->getMessage()}</p>");
  }
?>
  </body>
</html>
