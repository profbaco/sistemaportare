<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$idkm = $_REQUEST['idkm'];

$sql = $consulta->PDOSelecionarPadrao("*", "ZPortareViewMapaDiario", "id = " . $idkm);
$dmapa = new ArrayIterator($sql);
?>
<!DOCTYPE html>
<html lang="pt_br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Portare - Mapa de Carga Diário</title>

    <!-- Bootstrap -->
    <link href="../../theme/default/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../theme/default/css/style.css" rel="stylesheet">
    <link href="../../theme/default/css/datepicker.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        html,body{
            margin: 0px;
            padding: 0px;
            top: 0px;
        }
    </style>

</head>
<body>

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
                <td>' .$valor->cliente.'</td>
                <td>' .$valor->destino.'</td>
                <td>' .$valor->dtchegadacliente.'</td>
                <td>' .$valor->dtretornoempresa.'</td>
                <td>' .$valor->idmov.'</td>
                <td>' .$valor->usuario.'</td>
                <td>' .$valor->kmsaida.'</td>
                <td>' .$valor->kmchegada.'</td>
                <td>' .($valor->kmchegada-$valor->kmsaida).'</td>
                <td>' .$valor->observacao.'</td>
              </tr>';
        $dmapa->next();
    }
    ?>
</table>

</body>
</html>