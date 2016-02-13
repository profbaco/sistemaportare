<?php
require("../../dados/config.php");
require("../../dados/classes.php");
$consulta = new banco();

$sentenca = "SELECT b.codconta, b.codigo, a.descricao, b.saldo, 1 ordem,
 (SELECT sum(x.saldo) FROM ZPortarePlanocontas x WHERE x.codigo like b.codigo + '%' and REPLACE(LEFT(x.codigo, 2), '.', '') < 10) soma
FROM ZPortarePlanocontas b, CCONTA a WHERE b.codconta = a.codconta and REPLACE(LEFT(b.codigo, 2), '.', '') < 10 UNION
SELECT b.codconta, b.codigo, a.descricao, b.saldo, 2 ordem,
(SELECT sum(x.saldo) FROM ZPortarePlanocontas x WHERE x.codigo like b.codigo + '%' and REPLACE(LEFT(x.codigo, 2), '.', '') > 10) soma
FROM ZPortarePlanocontas b, CCONTA a WHERE b.codconta = a.codconta and REPLACE(LEFT(b.codigo, 2), '.', '') > 10";

$condicao = (empty($_REQUEST['movimentacao'])) ? ' 1=1 ' : ' soma <> 0';

echo '<link rel="stylesheet" type="text/css" href="theme/default/css/print.css" media="print">';


echo '<table class="table table-condensed table-hover table-striped">';
#Buscando os grupos de contas
$grupo = $consulta->PDOSelecionarPadrao("*", "ZPortareDregrupo", "");
$dgrupo = new ArrayIterator($grupo);
while ($dgrupo->valid()){
    $valorgrupo = $dgrupo->current();
    echo '<tr>
            <th colspan="3">'.$valorgrupo->codigo.' - '.$valorgrupo->descricao.'</th>
          </tr>
          <tr>
            <th>Código RM</th>
            <th>Código Portare</th>
            <th>Descrição</th>
          </tr>';
    $sql = "SELECT B.codconta, B.codigo, C.DESCRICAO, 1 ordem
            FROM ZPortareDremontagem A
                JOIN ZPortarePlanocontas B ON A.idconta = B.id
                JOIN CCONTA C ON B.codconta = C.CODCONTA
            WHERE A.idgrupo = ".$valorgrupo->id."
            AND REPLACE(LEFT(b.codigo, 2), '.', '') < 10

            UNION

            SELECT B.codconta, B.codigo, C.DESCRICAO, 2 ordem
            FROM ZPortareDremontagem A
                JOIN ZPortarePlanocontas B ON A.idconta = B.id
                JOIN CCONTA C ON B.codconta = C.CODCONTA
            WHERE A.idgrupo = ".$valorgrupo->id."
            AND REPLACE(LEFT(b.codigo, 2), '.', '') > 10";
    $contas = $consulta->PDOSelecionarPadrao("codconta, codigo, descricao, ordem", "(".$sql.") as tabela", " 1=1 ORDER BY ordem, codigo");
    $dconta = new ArrayIterator($contas);
    while ($dconta->valid()){
        $valorconta = $dconta->current();
        echo '<tr>
                 <td>'.$valorconta->codconta.'</td>
                 <td>'.$valorconta->codigo.'</td>
                 <td>'.$valorconta->descricao.'</td>
              </tr>';
        $dconta->next();
    }

    $dgrupo->next();
}

        /*<tr>
            <td>TESTE 1</td>
            <td>TESTE 2</td>
            <td>TESTE 3</td>
        </tr>
      </tbody>';*/

echo '</table>
    <br>
    <div class="text-center">'.'
        <a href="javascript:window.print()" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Imprimir </a>
    </div>
    <br>';