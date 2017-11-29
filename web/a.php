<html>
  <body>
      <h3>Inserir e remover categorias e sub-categorias</h3>
<?php
    //error_reporting(E_ALL);
    //ini_set('display_errors',1);

    try {
        include 'config.php';

        $mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : "";
        $nome = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : "";
        if ($mode == "add_cat" && $nome != "") {
            $db->query("start transaction;");

            try {
                $prep = $db->prepare("INSERT INTO categoria(nome) VALUES (?)");
                $prep->bindParam(1, $nome, PDO::PARAM_STR, 50);
                $prep->execute();

                $prep = $db->prepare("INSERT INTO categoria_simples(nome) VALUES (?)");
                $prep->bindParam(1, $nome, PDO::PARAM_STR, 50);
                $prep->execute();

                if (isset($_REQUEST['super_categoria']) && $_REQUEST['super_categoria'] != "none") {
                    $super_categoria = $_REQUEST['super_categoria'];

                    // verificar se $super_categoria e categoria_simples
                    $prep = $db->prepare("SELECT nome FROM categoria_simples WHERE nome = ?");
                    $prep->bindParam(1, $super_categoria, PDO::PARAM_STR, 50);
                    $prep->execute();
                    if ($prep->rowCount() > 0) {
                        // se sim, trocar de simples para super_categoria antes de inserir na relacao constituida
                        $prep = $db->prepare("DELETE FROM categoria_simples WHERE nome = ?");
                        $prep->bindParam(1, $super_categoria, PDO::PARAM_STR, 50);
                        $prep->execute();
                        $prep = $db->prepare("INSERT INTO super_categoria(nome) VALUES (?)");
                        $prep->bindParam(1, $super_categoria, PDO::PARAM_STR, 50);
                        $prep->execute();
                    }

                    try {
                        $prep = $db->prepare("INSERT INTO constituida(super_categoria, categoria) VALUES (?, ?)");
                        $prep->bindParam(1, $super_categoria, PDO::PARAM_STR, 50);
                        $prep->bindParam(2, $nome, PDO::PARAM_STR, 50);
                        $prep->execute();
                    }
                    catch (PDOException $e) {
                        echo("<h5><font color=\"red\">ERRO: A super categoria escolhida &eacute; inv&aacute;lida.</font></h5>");
                    }
                }
            }
            catch (PDOException $e) {
                echo("<h5><font color=\"red\">ERRO: O nome da categoria escolhida j&aacute; existe.</font></h5>");
            }

            $db->query("commit;");
        }
        if ($mode == "remove") {
            // verificar se existem produtos da categoria a remover
            $prep = $db->prepare("SELECT count(ean) FROM produto WHERE categoria = ?");
            $prep->bindParam(1, $nome, PDO::PARAM_STR, 50);
            $prep->execute();
            $produtosAssoc = $prep->fetch();
            if ($produtosAssoc['count'] > 0) {
                echo("<h5><font color=\"red\">ERRO: Existe {$produtosAssoc['count']} produto(s) associado(s) a esta categoria. Devem ser eliminados primeiro.</font></h5>");
            }
            else {
                // verificar $nome tem uma super categoria e se sim, verificar se e necessario converter para categoria simples
                $prep = $db->prepare("
                    SELECT count(categoria)
                    FROM constituida
                    WHERE super_categoria IN (
                        SELECT super_categoria
                        FROM constituida
                        WHERE categoria = ?
                    )");
                $prep->bindParam(1, $nome, PDO::PARAM_STR, 50);
                $prep->execute();
                $categoriasIrmas = $prep->fetch();
                $categoriasIrmas = $categoriasIrmas['count'];

                if ($categoriasIrmas == 1) {
                    $db->query("start transaction;");

                    $prep = $db->prepare("SELECT super_categoria FROM constituida WHERE categoria = ?");
                    $prep->bindParam(1, $nome, PDO::PARAM_STR, 50);
                    $prep->execute();
                    $super_name = $prep->fetch();
                    $super_name = $super_name['super_categoria'];
                }

                $prep = $db->prepare("DELETE FROM categoria WHERE nome = ?");
                $prep->bindParam(1, $nome, PDO::PARAM_STR, 50);
                $prep->execute();

                if ($categoriasIrmas == 1) {
                    $prep = $db->prepare("DELETE FROM super_categoria WHERE nome = ?");
                    $prep->bindParam(1, $super_name, PDO::PARAM_STR, 50);
                    $prep->execute();

                    $prep = $db->prepare("INSERT INTO categoria_simples VALUES (?)");
                    $prep->bindParam(1, $super_name, PDO::PARAM_STR, 50);
                    $prep->execute();

                    $db->query("commit;");
                }
            }
        }

        echo("<h4>Criar categoria</h4>");
        echo("<form action=\"a.php\" method=\"POST\">");
        echo("<input type=\"hidden\" name=\"mode\" value=\"add_cat\"/>");
        echo("<p>Nome da categoria: <input type=\"text\" name=\"nome\"/></p>");
        echo("<p>Categoria m&atilde;e: <select name=\"super_categoria\"><option value=\"none\">Nenhuma</option>");
        $prep = $db->prepare("SELECT nome FROM categoria ORDER BY nome");
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
        echo("<form action=\"a.php\" method=\"post\">
          <p><input type=\"hidden\" name=\"mode\" value=\"search\"/></p>
          <p>Nome: <input type=\"text\" name=\"nome_search\"/>
            <input type=\"submit\" value=\"Procurar\"/>
          </p>
        </form>");

        if ($mode == "search"){
            $nome = $_REQUEST['nome_search'];
            $nome = "%" . $nome;
            $nome = $nome . "%";

            if ($nome != "%%") {
                $prep = $db->prepare("SELECT nome FROM categoria WHERE nome LIKE ? ORDER BY nome");
                try{
                  $prep->bindParam(1, $nome, PDO::PARAM_STR, 50);
                  $prep->execute();
                }
                catch (PDOException $e){
                  echo("<p>ERRO: Nome nao valido.</p>");
                }
                $result = $prep->fetchAll();
            }
            else {
                $result = [];
            }

            if($result != FALSE || $result != []){
              echo("<table border=\"1\" cellspacing=\"5\" style=\"text-align: center\">\n");
              echo("<tr>\n");
              echo("<td><b>Nome</b></td>\n");
              echo("<td><b>Remover</b></td>\n");
              echo("</tr>\n");
            }
            $number_rows = count($result);
            echo("<p>Foram encontrada(s) $number_rows categoria(s).</p>");
            foreach($result as $row){
              echo("<tr>\n");
              echo("<td>{$row['nome']}</td>\n");
              echo("<td><a href=\"a.php?mode=remove&nome={$row['nome']}\">Remover</a></td>\n");
              echo("</tr>\n");
            }
            echo("</table>\n");
            echo("<br><br><a href='a.php'>Voltar</a><br><br>");
        }
        else {
            echo("<table border=\"1\" cellspacing=\"5\" style=\"text-align: center\">\n");
            echo("<tr>\n");
            echo("<td><b>Nome</b></td>\n");
            echo("<td><b>Remover</b></td>\n");
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



    }
    catch (PDOException $e){
      //echo("<p>ERROR: {$e->getMessage()}</p>");
    }
?>
  </body>
</html>
