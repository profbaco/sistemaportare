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

$sqlperiodo = $consulta->PDOSelecionarPadrao("CONVERT(VARCHAR(15), datainicial, 103) dtinicial, CONVERT(VARCHAR(15), datafinal, 103) dtfinal", "ZPortareDataBase", "1=1");
$dperiodo = new ArrayIterator($sqlperiodo);
$valorperiodo = $dperiodo->current();

echo '<link rel="stylesheet" type="text/css" href="theme/default/css/print.css" media="print">';

echo '<table class="table table-condensed table-hover table-striped'.'">
    <thead>
        <tr>
            <th>Código RM</th>
            <th>Código Portare</th>
            <th>Descrição</th>
            <th class="direita">Valor &nbsp;</th>
        </tr>
        <tr>
            <td><select id="movimentacao" class="form-control">
    <option value="" selected>Todas as Contas</option>
    <option value="1">Somente com Saldo</option>
</select></td>
            <td>
                <a href="javascript:void()" class="btn btn-default" onclick="verSomenteSaldo()">
                    <span class="glyphicon glyphicon-print"></span> Processar
                </a>
            </td>
            <td collspan="2" class="pull-right">Período de '.$valorperiodo->dtinicial.' até '.$valorperiodo->dtfinal.'&nbsp;</td>
        </tr>
    </thead>';

$sql = $consulta->PDOSelecionarPadrao("codconta, codigo, descricao, saldo, ordem, soma ", "(".$sentenca.") as tabela ", $condicao." order by ordem, codigo");
$d = new ArrayIterator($sql);
while ($d->valid()){
    $valor = $d->current();

    echo '<tbody>
        <tr>
            <td>'.$valor->codconta.'</td>
            <td>'.$valor->codigo.'</td>
            <td>'.$valor->descricao.'</td>
            <td class="direita">'.number_format($valor->soma, 2, ',', '.').' &nbsp;</td>
        </tr>
    </tbody>';

    $d->next();
}
echo '</table>
    <br>
    <div class="text-center">
        <a href="javascript:window.print()" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Imprimir </a>
    </div>
    <br>';