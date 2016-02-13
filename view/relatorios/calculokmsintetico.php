<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$idkm = $_REQUEST['idkm'];
?>
<!DOCTYPE html>
<html lang="pt_br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Portare - Relatório Sintético do Km</title>

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
        $totalagregado = $totalagregado + $m->calculado;
        $d->next();
    }
    ?>
    <tr>
        <td>&nbsp; <strong>Total de Caminhões</strong></td>
        <td><strong><?php echo number_format($totalagregado,2,',','.'); ?></strong></td>
    </tr>

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
        $totalagregado = $totalagregado + $m->calculado;
        $d->next();
    }
    ?>
    <tr>
        <td>&nbsp; <strong>Total de Despesas Fixas</strong></td>
        <td><strong><?php echo number_format($totalagregado,2,',','.'); ?></strong></td>
    </tr>

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
        $totalagregado = $totalagregado + $m->calculado;
        $d->next();
    }
    ?>
    <tr>
        <td>&nbsp; <strong>Total de Despesas Adicionais</strong></td>
        <td><strong><?php echo number_format($totalagregado,2,',','.'); ?></strong></td>
    </tr>
</table>


<table class="table table-hover table-condensed">
    <thead>
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
            $totalCliFor = $totalCliFor + $mClifor->calculado;
            $dCliFor->next();
        }
        echo '<tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <td>&nbsp;<strong>Total de Despesas Cliente</strong></td>
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