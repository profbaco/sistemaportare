<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mapa Resumo de Cálculo de KM</title>
    <link rel="stylesheet" type="text/css" href="../../theme/default/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../theme/default/css/print.css" media="print">

    <style>
        body {
            padding-top: 0px;
        }
    </style>
</head>

<body>

<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$sqlperiodo = $consulta->PDOSelecionarPadrao("CONVERT(VARCHAR(15), datainicial, 103) dtinicial, datainicial, CONVERT(VARCHAR(15), datafinal, 103) dtfinal, datafinal", "ZPortareDataBase", "id=1");
$dperiodo = new ArrayIterator($sqlperiodo);
$valorperiodo = $dperiodo->current();

$datainicial = $valorperiodo->datainicial;
$datafinal = $valorperiodo->datafinal;

$idkm = $_REQUEST['idkm'];
$placa = $_REQUEST['placa'];


#Buscando os KM rodados no sistema
$vlkm = $consulta->PDOSelecionarPadrao("sum(kmpercorrido) totalkm", "ZPortareKm", "idcaminhao = '".$placa."'");
$dkm = new ArrayIterator($vlkm);
$totalkm = $dkm->current();

#Descobrindo o código do centro de custo
$codsql = $consulta->PDOSelecionarPadrao("CODCCUSTO, NOME","GCCUSTO","CODCOLIGADA=5 AND NOME LIKE '".$placa."%'");
$dccusto = new ArrayIterator($codsql);
$codccusto = $dccusto->current();

#Buscando os valores por cada conta movimentada
$sqlsaldo = $consulta->PDOSelecionarPadrao("A.codconta, A.codigo, sum(A.saldo) saldototal",
"ZPortareViewContas A", "A.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND A.codccusto = '".$codccusto->CODCCUSTO."' GROUP BY A.codconta, A.codigo");
$dsaldo = new ArrayIterator($sqlsaldo);

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4><strong>
                Relatório de Mapa Resumo de Cálculo de KM - Centro de Custo: </strong><?php
            echo $codccusto->CODCCUSTO . ' - ' . $codccusto->NOME;
            ?>

        </h4>
        Periodo de <?php echo $valorperiodo->dtinicial; ?> até <?php echo $valorperiodo->dtfinal; ?>
    </div>
    <div class="panel-body">


        <table class="table table-condensed">
            <thead>
            <tr>
                <th>Código RM</th>
                <th>Código Portare</th>
                <th>Valor</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($dsaldo->valid()){
                $valorsaldo = $dsaldo->current();
                echo '<tr>
                        <td>'.$valorsaldo->codconta.'</td>
                        <td>'.$valorsaldo->codigo.'</td>
                        <td>'.number_format(($valorsaldo->saldototal/$totalkm->totalkm),4,',','.').'</td>
                      </tr>';
                $dsaldo->next();
            }
            ?>
            </tbody>
        </table>

    </div>
    <div class="panel-footer">
        <div class="text-center">
            <a href="javascript:window.print()" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Imprimir </a>
        </div>
    </div>
</div>
</body>
</html>