<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$idkm = $_REQUEST['idkm'];


$sqlperiodo = $consulta->PDOSelecionarPadrao("CONVERT(VARCHAR(15), datainicial, 103) dtinicial, CONVERT(VARCHAR(15), datafinal, 103) dtfinal", "ZPortareDataBase", "1=1");
$dperiodo = new ArrayIterator($sqlperiodo);
$valorperiodo = $dperiodo->current();
?>
<!DOCTYPE html>
<html lang="pt_br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Portare - Relatório Geral do KM</title>

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
        body{
            margin: 0px;
            padding: 0px;
            top: 0px;
        }
    </style>

</head>
<body>

<table class="table table-hover table-condensed">
    <thead>
    <tr>
        <th colspan="5"><h3>Caminhões e Agregados</h3></th>
    </tr>
    <tr>
        <th>Caminhão</th>
        <th>Despesa</th>
        <th>Período</th>
        <th>Valor</th>
        <th>Calculado</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $dados = $consulta->PDOSelecionar("A.id,
                                        CASE WHEN A.agregado = 0 THEN A.idcaminhao ELSE '**'+A.idcaminhao END idcaminhao,
                                        B.descricao, CASE A.periododiario
                                         when 365 then 'Anual'
                                         when 180 then 'Semestral'
                                         when 90 then 'Trimestral'
                                         when 60 then 'Bimestral'
                                         when 30 then 'Mensal'
                                         when 1 then 'Diário'
                                         end periodo, A.valor, A.calculado", "ZPortareKmDespesas A, ZPortareDespesas B",
        "A.iddespesa = B.id AND A.idkm = ".$idkm." AND A.agregado in (0,1)",
        0, 600, "idcaminhao DESC");
    $d = new ArrayIterator($dados);
    $totalagregado = 0;
    while ($d->valid()){
        $m = $d->current();
        echo '<tr>
                                <td>'.$m->idcaminhao.'</td>
                                <td>'.$m->descricao.'</td>
                                <td>'.$m->periodo.'</td>
                                <td>'.number_format($m->valor,2,',','.').'</td>
                                <td>'.number_format($m->calculado,2,',','.').'</td>
                              </tr>';
        $totalagregado = $totalagregado + $m->calculado;
        $d->next();
    }
    ?>
    </tbody>
    <tfoot>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp; <strong>Total</strong></td>
        <td><strong><?php echo number_format($totalagregado,2,',','.'); ?></strong></td>
    </tr>
    </tfoot>
</table>


<table class="table table-hover table-condensed">
    <thead>
    <tr>
        <th colspan="4"><h3>Despesas Fixas</h3></th>
    </tr>
    <tr>
        <th>Despesa</th>
        <th>Período</th>
        <th>Valor</th>
        <th>Calculado</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $dados = $consulta->PDOSelecionar("A.id,
                                        CASE WHEN A.agregado = 0 THEN A.idcaminhao ELSE '**'+A.idcaminhao END idcaminhao,
                                        B.descricao, CASE A.periododiario
                                         when 365 then 'Anual'
                                         when 180 then 'Semestral'
                                         when 90 then 'Trimestral'
                                         when 60 then 'Bimestral'
                                         when 30 then 'Mensal'
                                         when 1 then 'Diário'
                                         end periodo, A.valor, A.calculado", "ZPortareKmDespesas A, ZPortareDespesas B",
        "A.iddespesa = B.id AND A.idkm = ".$idkm." AND A.agregado in (2)",
        0, 600, "idcaminhao DESC");
    $d = new ArrayIterator($dados);
    $totalagregado = 0;
    while ($d->valid()){
        $m = $d->current();
        echo '<tr>
                                <td>'.$m->descricao.'</td>
                                <td>'.$m->periodo.'</td>
                                <td>'.number_format($m->valor,2,',','.').'</td>
                                <td>'.number_format($m->calculado,2,',','.').'</td>
                              </tr>';
        $totalagregado = $totalagregado + $m->calculado;
        $d->next();
    }
    ?>
    </tbody>
    <tfoot>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp; <strong>Total</strong></td>
        <td><strong><?php echo number_format($totalagregado,2,',','.'); ?></strong></td>
    </tr>
    </tfoot>
</table>

<table class="table table-hover table-condensed">
    <thead>
    <tr>
        <th colspan="4"><h3>Despesas Adicionais</h3></th>
    </tr>
    <tr>
        <th>Despesa</th>
        <th>Período</th>
        <th>Valor</th>
        <th>Calculado</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $dados = $consulta->PDOSelecionar("A.id,
                                        CASE WHEN A.agregado = 0 THEN A.idcaminhao ELSE '**'+A.idcaminhao END idcaminhao,
                                        B.descricao, CASE A.periododiario
                                         when 365 then 'Anual'
                                         when 180 then 'Semestral'
                                         when 90 then 'Trimestral'
                                         when 60 then 'Bimestral'
                                         when 30 then 'Mensal'
                                         when 1 then 'Diário'
                                         end periodo, A.valor, A.calculado", "ZPortareKmDespesas A, ZPortareDespesas B",
        "A.iddespesa = B.id AND A.idkm = ".$idkm." AND A.agregado in (3)",
        0, 600, "idcaminhao DESC");
    $d = new ArrayIterator($dados);
    $totalagregado = 0;
    while ($d->valid()){
        $m = $d->current();
        echo '<tr>
                                <td>'.$m->descricao.'</td>
                                <td>'.$m->periodo.'</td>
                                <td>'.number_format($m->valor,2,',','.').'</td>
                                <td>'.number_format($m->calculado,2,',','.').'</td>
                              </tr>';
        $totalagregado = $totalagregado + $m->calculado;
        $d->next();
    }
    ?>
    </tbody>
    <tfoot>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp; <strong>Total</strong></td>
        <td><strong><?php echo number_format($totalagregado,2,',','.'); ?></strong></td>
    </tr>
    </tfoot>
</table>


<table class="table table-hover table-condensed">
    <thead>
    <tr>
        <th colspan="7"><h3>Clientes Vinculados ao KM</h3></th>
    </tr>
    <tr>
        <th>Cliente</th>
        <th>Dias</th>
        <th>Km (Ida e Volta)</th>
        <th>Dt Saída</th>
        <th>Combustível</th>
        <th>Volume</th>
        <th>Valor KM</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $condicao = "A.idcliente = B.CODCFO AND A.idcombustivel = C.id AND B.CODCOLIGADA IN (5) AND A.idkm = " . $idkm;
    $dados = $consulta->PDOSelecionar("A.id, A.idcliente, B.CODCFO, B.nome, (A.kmasfalto+A.kmchao) totalkm, CONVERT(VARCHAR(15), A.datasaida, 103) datasaida, C.descricao combustivel, A.volume, A.valorkm, A.diasviagem",
        "ZPortareKmClientes A, FCFO B, ZPortareCombustivel C", $condicao, 0, 100, "B.nome");
    $d = new ArrayIterator($dados);
    while ($d->valid()){
        $m = $d->current();
        echo '<tr id="'.$m->id.'">
                                <td>'.$m->CODCFO.' - '.$m->nome.'</td>
                                <td>'.$m->diasviagem.'</td>
                                <td>'.number_format(($m->totalkm*2), 0, ',', '.').'</td>
                                <td>'.$m->datasaida.'</td>
                                <td>'.$m->combustivel.'</td>
                                <td>'.number_format($m->volume, 0, ',', '.').'</td>
                                <td>'.number_format($m->valorkm, 2, ',', '.').'</td>
                              </tr>';
        echo '<thead><tr>
                <th>&nbsp;</th>
                <th>Despesa do Cliente</th>
                <th>Período</th>
                <th>Valor</th>
                <th>Calculado</th>
              </tr></thead>';
        $dadosCliFor = $consulta->PDOSelecionar("A.id,
                                        CASE WHEN A.agregado = 0 THEN A.idcaminhao ELSE '**'+A.idcaminhao END idcaminhao,
                                        B.descricao, CASE A.periododiario
                                         when 365 then 'Anual'
                                         when 180 then 'Semestral'
                                         when 90 then 'Trimestral'
                                         when 60 then 'Bimestral'
                                         when 30 then 'Mensal'
                                         when 1 then 'Diário'
                                         end periodo, A.valor, A.calculado", "ZPortareKmDespesas A, ZPortareDespesas B",
            "A.iddespesa = B.id AND A.idkm = ".$idkm." AND A.agregado in (4) AND A.idcliente='".$m->CODCFO."'",
            0, 600, "idcaminhao DESC");
        $dCliFor = new ArrayIterator($dadosCliFor);
        $totalCliFor = 0;
        while ($dCliFor->valid()){
            $mClifor = $dCliFor->current();
            echo '<tr>
                        <th>&nbsp;</th>
                        <td>'.$mClifor->descricao.'</td>
                        <td>'.$mClifor->periodo.'</td>
                        <td>'.number_format($mClifor->valor,2,',','.').'</td>
                        <td>'.number_format($mClifor->calculado,2,',','.').'</td>
                  </tr>';
            $totalCliFor = $totalCliFor + $mClifor->calculado;
            $dCliFor->next();
        }
        echo '<tr>
                        <th>&nbsp;</th>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;<strong>Total do Cliente</strong></td>
                        <td><strong>'.number_format($totalCliFor,2,',','.').'</strong></td>
                  </tr>';
        $d->next();
    }
    ?>
    </tbody>
</table>

<small>
    * Cálculo do KM é como base o Total Calculado:  {[(Caminhões e Agregados) + (Despesas Fixas) + (Despesas Adicionais)] + (Cliente)} * (Dias de viagem)} / Km
</small>
</body>
</html>