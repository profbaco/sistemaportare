<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

list($diac,$mesc,$anoc)=explode("/", $_REQUEST['dtinicial']);
$dtinicial = $anoc.'-'.$mesc.'-'.$diac;
list($diac,$mesc,$anoc)=explode("/", $_REQUEST['dtfinal']);
$dtfinal = $anoc.'-'.$mesc.'-'.$diac;


#echo "SELECT * FROM ZPortareViewMapaDiario WHERE saidaempresa BETWEEN '" . $dtinicial . "' and '" .$dtfinal. "'";

$sql = $consulta->PDOSelecionarPadrao("*", "ZPortareViewMapaDiario", "saidaempresa BETWEEN '" . $dtinicial . "' and '" .$dtfinal. "'");
$dmapa = new ArrayIterator($sql);
?>

<table class="table table-bordered table-condensed">
    <tr>
        <th>#</th>
        <th>Motorista</th>
        <th>Frota</th>
        <th>Quantidade</th>
        <th>Carga</th>
        <th>Origem</th>
        <th>Hr Saída</th>
        <th>Saída Emp</th>
        <th>Saída Cli</th>
        <th>Dif</th>
        <th>Cliente</th>
        <th>Destino</th>
        <th>Cheg Cli</th>
        <th>Cheg Emp</th>
        <th>Id. Mov</th>
        <th>Responsável</th>
        <th>Km Inicial</th>
        <th>Km Final</th>
        <th>Km Rodado</th>
        <th>Observação</th>
    </tr>
    <?php
    while ($dmapa->valid()) {
        $valor = $dmapa->current();
        echo '<tr>
                <td>' .$valor->id.'</td>
                <td>' .$valor->motorista.'</td>
                <td>' .$valor->idcaminhao.'</td>
                <td>' .$valor->volume.'</td>
                <td>' .$valor->carga.'</td>
                <td>' .$valor->origem.'</td>
                <td>' .$valor->horasaida.'</td>
                <td>' .$valor->dtsaidaempresa.'</td>
                <td>' .$valor->dtsaidacliente.'</td>
                <td>' .$valor->diasdiferenca.'</td>
                <td>' .$valor->cliente.'</td>
                <td>' .$valor->destino.'</td>
                <td>' .$valor->dtchegadacliente.'</td>
                <td>' .$valor->dtretornoempresa.'</td>
                <td>' .$valor->idmov.'</td>
                <td>' .$valor->usuario.'</td>
                <td>' .$valor->kmsaida.'</td>
                <td>' .$valor->kmchegada.'</td>
                <td>' .$valor->kmpercorrido.'</td>
                <td>' .$valor->observacao.'</td>
              </tr>';
        $dmapa->next();
    }
    ?>
</table>
<button class="btn btn-block btn-warning" onclick="window.print()">
    <i class="glyphicon glyphicon-print"></i> Imprimir
</button>