<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resultado da DRE</title>
    <link rel="stylesheet" type="text/css" href="../../theme/default/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../theme/default/css/style.css">
    <link rel="stylesheet" type="text/css" href="../../theme/default/css/print.css" media="print">

</head>

<body>

<?php

require("../../dados/config.php");
require("../../dados/classes.php");
$consulta = new banco();

$nivel = $_REQUEST['vernivel'];
$ordernarpor = $_REQUEST['ordenarpor'];
$imprimirconta = $_REQUEST['imprimirconta'];

if ($nivel == 1) $condicao = 'LEN(b.codigo) <= 1';
elseif ($nivel == 2) $condicao = 'LEN(b.codigo) <= 3';
elseif ($nivel == 3) $condicao = 'LEN(b.codigo) <= 5'; #6';
elseif ($nivel == 4) $condicao = 'LEN(b.codigo) <= 7'; #9';
elseif ($nivel == 5) $condicao = 'LEN(b.codigo) <= 9'; #13';
elseif ($nivel == 6) $condicao = 'LEN(b.codigo) <= 13'; #17';


$situacao = ($imprimirconta==2) ? ' 1=1 ' : ' soma <> 0';

if ($ordernarpor == 1) $ordem = "codigo";
elseif ($ordernarpor == 2) $ordem = "codconta";

$sqlperiodo = $consulta->PDOSelecionarPadrao("CONVERT(VARCHAR(15), datainicial, 103) dtinicial, CONVERT(VARCHAR(15), datafinal, 103) dtfinal", "ZPortareDataBase", "1=1");
$dperiodo = new ArrayIterator($sqlperiodo);
$valorperiodo = $dperiodo->current();

echo '<h3 class="centro">'.'Relatório de D.R.E.</h3>';
echo '<div class="centro">Período de '.$valorperiodo->dtinicial.' até '.$valorperiodo->dtfinal.'</div>';

$sqlgrupo = $consulta->PDOSelecionarPadrao("*", "ZPortareDregrupo", "1=1 order by id");
$dgrupo = new ArrayIterator($sqlgrupo);
echo '<table class="table table-condensed table-hover table-striped table-bordered'.'">
    <thead>
        <tr>
            <th>Código RM</th>
            <th>Código Portare</th>
            <th>Descrição</th>
            <th>Valor</th>
        </tr>
    </thead>';
$totalgrupo = 0;
while ($dgrupo->valid()) {
    $valorgrupo = $dgrupo->current();

    $sentenca = "SELECT b.codconta, b.codigo, a.descricao, b.saldo, 1 ordem,
                 (SELECT sum(x.saldo) FROM ZPortarePlanocontas x, ZPortareDremontagem z WHERE x.id = z.idconta and z.idgrupo = c.idgrupo and x.codigo like b.codigo + '%' and REPLACE(LEFT(x.codigo, 2), '.', '') < 10) soma
                FROM ZPortarePlanocontas b, CCONTA a, ZPortareDremontagem c
                WHERE b.codconta = a.codconta
                and REPLACE(LEFT(b.codigo, 2), '.', '') < 10
                and b.id = c.idconta
                and c.idgrupo = ".$valorgrupo->id."
                and ".$condicao."

                UNION

                SELECT b.codconta, b.codigo, a.descricao, b.saldo, 2 ordem,
                (SELECT sum(x.saldo) FROM ZPortarePlanocontas x, ZPortareDremontagem z WHERE x.id = z.idconta and z.idgrupo = c.idgrupo and x.codigo like b.codigo + '%' and REPLACE(LEFT(x.codigo, 2), '.', '') > 10) soma
                FROM ZPortarePlanocontas b, CCONTA a, ZPortareDremontagem c
                WHERE b.codconta = a.codconta
                and REPLACE(LEFT(b.codigo, 2), '.', '') > 10
                and b.id = c.idconta
                and ".$condicao."
                and c.idgrupo = ".$valorgrupo->id;

    $totalsaldo = 0;
    $sqlconta = $consulta->PDOSelecionarPadrao("codigo, descricao, codconta, soma, ordem", "(".$sentenca.") as tabela", $situacao." order by ordem, ".$ordem);
    $dconta = new ArrayIterator($sqlconta);
    while ($dconta->valid()){
        $valorconta = $dconta->current();
        echo '<tr>
                <td>'.$valorconta->codconta.'</td>
                <td>'.$valorconta->codigo.'</td>
                <td>'.$valorconta->descricao.'</td>
                <td><div class="direita">'.number_format($valorconta->soma, 2, ',', '.').'</div></td>
              </tr>';
        if (strlen($valorconta->codigo) > 11) $totalsaldo = $totalsaldo + $valorconta->soma;
        $dconta->next();
    }

    $totalgrupo = $totalgrupo + $valorgrupo->valor;

    echo '<tr>
            <th colspan="3">Grupo: '.$valorgrupo->codigo.' - '.$valorgrupo->descricao.'</th>
            <th><div class="direita">'.number_format($totalgrupo, 2, ',', '.').'</div></th>
        </tr>';

    $dgrupo->next();
}

echo '</table>';
?>

<br>
<div class="text-center">
    <a href="javascript:window.print()" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Imprimir </a>
</div>
<br>

</body>
</html>