<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

list($diac,$mesc,$anoc)=explode("/", $_REQUEST['dtinicial']);
$dtinicial = $anoc.'-'.$mesc.'-'.$diac;
list($diac,$mesc,$anoc)=explode("/", $_REQUEST['dtfinal']);
$dtfinal = $anoc.'-'.$mesc.'-'.$diac;
$idcaminhao = $_REQUEST['idcaminhao'];


#echo "SELECT * FROM ZPortareViewMapaDiario WHERE saidaempresa BETWEEN '" . $dtinicial . "' and '" .$dtfinal. "'";

$sql = $consulta->PDOSelecionarPadrao("id, dtsaidaempresa, motorista, saidaempresa, kmrodado, litragem, (kmrodado/litragem) mediaviagem",
    "(
    SELECT DISTINCT A.id, A.dtsaidaempresa, A.motorista, A.saidaempresa,
    ((SELECT X.km FROM ZPortareKmAbastecimentos X WITH (NOLOCK) WHERE X.idkm = A.id AND X.tipo = 'C') - 
    (SELECT X.km FROM ZPortareKmAbastecimentos X WITH (NOLOCK) WHERE X.idkm = A.id AND X.tipo = 'S')) kmrodado,
    (SELECT SUM(X.litragem) FROM ZPortareKmAbastecimentos X WITH (NOLOCK) WHERE X.idkm = A.id AND X.tipo <> 'S') litragem
    FROM ZPortareViewMapaDiario A WITH (NOLOCK)
    WHERE A.idcaminhao = '".$idcaminhao."'
    AND A.saidaempresa BETWEEN '".$dtinicial."' and '".$dtfinal."'
) AS TABELA", "1=1 ORDER BY saidaempresa");
$dmapa = new ArrayIterator($sql);
?>

<table class="table table-bordered table-condensed">
    <tr>
        <th>#</th>
        <th>Dt Saída</th>
        <th>Motorista</th>
        <th>Km Rodado</th>
        <th>Litragem</th>
        <th>Média</th>
    </tr>
    <?php
    while ($dmapa->valid()) {
        $valor = $dmapa->current();
        echo '<tr>
                <td>' .$valor->id.'</td>
                <td>' .$valor->dtsaidaempresa.'</td>
                <td>' .$valor->motorista.'</td>
                <td>' .number_format($valor->kmrodado,0,',','.').'</td>
                <td>' .number_format($valor->litragem,2,',','.').'</td>
                <td>' .number_format($valor->mediaviagem,2,',','.').'</td>
              </tr>';
        $dmapa->next();
    }
    ?>
</table>
<button class="btn btn-block btn-warning" onclick="window.print()">
    <i class="glyphicon glyphicon-print"></i> Imprimir
</button>