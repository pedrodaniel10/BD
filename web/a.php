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

            try {
                $prep = $db->prepare("INSERT INTO categoria(nome) VALUES (:nome)");
                $prep->bindParam(":nome", $nome);
                $prep->execute();

                $prep = $db->prepare("INSERT INTO categoria_simples(nome) VALUES (:nome)");
                $prep->bindParam(":nome", $nome);
                $prep->execute();

                if (isset($_REQUEST['super_categoria']) && $_REQUEST['super_categoria'] != "none") {
                    $super_categoria = $_REQUEST['super_categoria'];

                    // verificar se $super_categoria e categoria_simples
                    $prep = $db->prepare("SELECT nome FROM categoria_simples WHERE nome = :nome");
                    $prep->bindParam(":nome", $super_categoria);
                    $prep->execute();
                    if ($prep->rowCount() > 0) {
                        // se sim, trocar de simples para super_categoria antes de inserir na relacao constituida
                        $prep = $db->prepare("DELETE FROM categoria_simples WHERE nome = :nome");
                        $prep->bindParam(":nome", $super_categoria);
                        $prep->execute();
                        $prep = $db->prepare("INSERT INTO super_categoria(nome) VALUES (:nome)");
                        $prep->bindParam(":nome", $super_categoria);
                        $prep->execute();
                    }

                    try {
                        $prep = $db->prepare("INSERT INTO constituida(super_categoria, categoria) VALUES (:super_categoria, :nome)");
                        $prep->bindParam(":super_categoria", $super_categoria);
                        $prep->bindParam(":nome", $nome);
                        $prep->execute();
                    }
                    catch (PDOException $e) {
                        echo("<h5><font color=\"red\">A super categoria escolhida &eacute; inv&aacute;lida.</font></h5>");
                    }
                }
            }
            catch (PDOException $e) {
                echo("<h5><font color=\"red\">O nome da categoria escolhida j&aacute; existe.</font></h5>");
            }

            $db->query("commit;");
        }
        if ($mode == "remove") {
            $db->query("start transaction;");

            // verificar se existem produtos da categoria a remover
            $prep = $db->prepare("SELECT ean FROM produto WHERE categoria = :nome");
            $prep->bindParam(":nome", $nome);
            $prep->execute();
            if ($prep->rowCount() > 0) {
                // se sim, TODO
            }

            $prep = $db->prepare("DELETE FROM categoria WHERE nome = :nome");
            $prep->bindParam(":nome", $nome);
            $prep->execute();

            $db->query("commit;");
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
            echo("<td><a href=\"a.php?mode=remove&nome={$row['nome']}\">Remover</a></td>\n");
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
