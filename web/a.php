<html>
  <body>
      <h3>Inserir e remover categorias e sub-categorias</h3>
<?php
    error_reporting(E_ALL);
    ini_set('display_errors',1);

    try {
        include 'config.php';

        $mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : "";
        $nome = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : "";
        if ($mode == "add_cat") {
            $db->query("start transaction;");

            $prep = $db->prepare("INSERT INTO categoria(nome) VALUES (:nome)");
            $prep->bindParam(":nome", $nome);
            $prep->execute();

            $prep = $db->prepare("INSERT INTO categoria_simples(nome) VALUES (:nome)");
            $prep->bindParam(":nome", $nome);
            $prep->execute();

            $db->query("commit;");
        }
        if ($mode == "delete") {
            //
        }

        echo("<h4>Criar categoria</h4>");
        echo("<form action=\"a.php\" method=\"POST\">");
        echo("<input type=\"hidden\" name=\"mode\" value=\"add_cat\"/>");
        echo("<p>Nome da categoria: <input type=\"text\" name=\"nome\"/></p>");
        echo("<p>Categoria m&atilde;e: <select name=\"super_categoria\"><option value=\"none\">Nenhuma</option>");
        $prep = $db->prepare("SELECT nome FROM categoria");
        $prep->execute();
        $result = $prep->fetchAll();
        foreach($result as $row) {
            echo("<option value=\"{$row['nome']}\">");
            echo($row['nome']);
            echo("</option>");
        }
        echo("</select></p>");
        echo("<p><input type=\"submit\" value=\"Submeter\"/></p>");
        echo("</form>");

        echo("<h4>Eliminar categoria</h4>");
        echo("<table border=\"1\" cellspacing=\"5\" style=\"text-align: center\">\n");
        echo("<tr>\n");
        echo("<td><b>Nome</b></td>\n");
        echo("<td>&nbscp;</td>\n");
        echo("</tr>\n");
        foreach($result as $row){
            echo("<tr>\n");
            echo("<td>{$row['nome']}</td>\n");
            echo("<td><a href=\"a.php?mode=delete&nome={$row['nome']}\">Remover</a></td>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
        echo("<br><br><a href='index.html'>Voltar</a><br><br>");

    }
    catch (PDOException $e){
      echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
  </body>
</html>
