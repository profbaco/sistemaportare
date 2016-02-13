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

$sqlperiodo = $consulta->PDOSelecionarPadrao("CONVERT(VARCHAR(15), datainicial, 103) dtinicial, datainicial, CONVERT(VARCHAR(15), datafinal, 103) dtfinal, datafinal", "ZPortareDataBase", "1=1");
$dperiodo = new ArrayIterator($sqlperiodo);
$valorperiodo = $dperiodo->current();

$datainicial = $valorperiodo->datainicial;
$datafinal = $valorperiodo->datafinal;

$chapa = $_REQUEST['chapa'];

$sqlmotorista = $consulta->PDOSelecionarPadrao("C.CODVEN, B.NOME, A.CHAPA", "PFUNC A
	JOIN PPESSOA B ON B.CODIGO = A.CODPESSOA
	JOIN TVEN C ON C.CODPESSOA = B.CODIGO", "A.CHAPA = '".$chapa."'");
$dmotorista = new ArrayIterator($sqlmotorista);
$valormotorista = $dmotorista->current();

$idmotorista = $valormotorista->CODVEN;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4><strong>
                Relatório de Ponto de Equilíbrio - Por Motorista: <?php echo '('.$valormotorista->CODVEN.') ' . $valormotorista->CHAPA.' - ' . $valormotorista->NOME; ?>
            </strong>
        </h4>
        Periodo de <?php echo $valorperiodo->dtinicial; ?> até <?php echo $valorperiodo->dtfinal; ?>
    </div>
    <div class="panel-body">

        <table class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>Conta Portare</th>
                <th>Conta</th>
                <th><div class="direita"> Valor</div></th>
            </tr>
            </thead>
            <?php
            #pegando os dados do grupo 2
            $sqlgrupo2 = $consulta->PDOSelecionarPadrao("A.codigo, A.descricao, SUM(D.saldo) valor", "ZPortareDregrupo A
                JOIN ZPortareDremontagem B ON A.id = B.idgrupo
                JOIN ZPortarePlanocontas C ON C.id = B.idconta
                JOIN ZPortareViewContas D ON D.codconta = C.codconta AND D.codigo = C.codigo",
                "D.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND D.chapa = '".$chapa."' AND A.id = 1 GROUP BY A.codigo, A.descricao");
            $dgrupo2 = new ArrayIterator($sqlgrupo2);
            $valorgrupo2 = $dgrupo2->current();
            echo '<tr>
                    <th>'.$valorgrupo2->codigo.'</th>
                    <th>'.$valorgrupo2->descricao.'</th>
                    <th class="direita">'.number_format($valorgrupo2->valor, 2, ',', '.').'</th>
                  </tr>';

            #pegando os custos das contas do 3
            $sqlconta3 = $consulta->PDOSelecionarPadrao("SUM(C.saldo) valor", "ZPortarePlanocontas B
	            JOIN ZPortareViewContas C ON B.codconta = C.codconta AND B.codigo = C.codigo",
                "C.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND B.codigo like '3.%' AND C.chapa = '".$chapa."'");
            $dconta3 = new ArrayIterator($sqlconta3);
            $valorconta3 = $dconta3->current();
            echo '<tr>
                    <td>3</td>
                    <td>CUSTOS VARIÁVEIS DA FROTA</td>
                    <td class="direita">'.number_format($valorconta3->valor, 2, ',', '.').'</td>
                  </tr>';

            #pegando os dados do grupo 4
            $sqlgrupo4 = $consulta->PDOSelecionarPadrao("A.codigo, A.descricao, SUM(D.saldo) valor", "ZPortareDregrupo A
                  JOIN ZPortareDremontagem B ON A.id = B.idgrupo
                  JOIN ZPortarePlanocontas C ON C.id = B.idconta
                  JOIN ZPortareViewContas D ON D.codconta = C.codconta AND D.codigo = C.codigo",
                "D.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND D.chapa = '".$chapa."' AND A.id = 3 GROUP BY A.codigo, A.descricao");
            $dgrupo4 = new ArrayIterator($sqlgrupo4);
            $valorgrupo4 = $dgrupo4->current();
            echo '<tr>
                    <th>'.$valorgrupo4->codigo.'</th>
                    <th>'.$valorgrupo4->descricao.'</th>
                    <th class="direita">'.number_format(($valorgrupo2->valor+$valorgrupo4->valor), 2, ',', '.').'</th>
                  </tr>';

            echo '<tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>';

            #Pegando as contas do plano de contas
            $sql = "SELECT codigo, descricao, 1 ordem FROM ZPortarePlanocontas
                    WHERE codigo in ('5', '6', '8', '9')
                    union
                    SELECT codigo, descricao, 2 ordem FROM ZPortarePlanocontas
                    WHERE codigo in ('10.1', '11', '12', '13.1', '14.1', '15', '16', '17', '19.1', '20')";
            $sqlcontas = $consulta->PDOSelecionarPadrao("codigo, descricao, ordem, 0 saldo", "(".$sql.") as tabela", " 1=1 order by ordem, codigo");
            $dcontas = new ArrayIterator($sqlcontas);
            $gastosoperacionais = 0;
            while ($dcontas->valid()){
                $valorcontas = $dcontas->current();
                $saldoconta = $consulta->PDOSelecionarPadrao("sum(saldo) saldo", "ZPortareViewContas", "codigo like '".$valorcontas->codigo."%' and chapa = '".$chapa."'");
                $dsaldo = new ArrayIterator($saldoconta);
                $valorsaldo = $dsaldo->current();
                echo '<tr>
                    <td>'.$valorcontas->codigo.'</td>
                    <td>'.$valorcontas->descricao.'</td>
                    <td class="direita">'.number_format($valorsaldo->saldo, 2, ',', '.').'</td>
                  </tr>';
                $gastosoperacionais = $gastosoperacionais +$valorsaldo->saldo;
                $dcontas->next();
            }
            echo '<tr>
                    <th></th>
                    <th>Gastos Operacionais ADM/TRIBT/FINANC</th>
                    <th class="direita">'.number_format($valorconta3->valor, 2, ',', '.').'</th>
                  </tr>';


            #pegando os dados do grupo 21
            $sqlgrupo21 = $consulta->PDOSelecionarPadrao("codigo, descricao,
                (SELECT SUM(D.saldo) saldo
                FROM ZPortareDregrupo A
                  JOIN ZPortareDremontagem B ON A.id = B.idgrupo
                  JOIN ZPortarePlanocontas C ON C.id = B.idconta
                  JOIN ZPortareViewContas D ON D.codconta = C.codconta AND D.codigo = C.codigo
                WHERE D.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND D.chapa = '".$chapa."' AND A.id < 6) valor", "ZPortareDregrupo", "codigo = 21");
            $dgrupo21 = new ArrayIterator($sqlgrupo21);
            $valorgrupo21 = $dgrupo21->current();
            echo '<tr>
                    <th>'.$valorgrupo21->codigo.'</th>
                    <th>'.$valorgrupo21->descricao.'</th>
                    <th class="direita">'.number_format($valorgrupo21->valor, 2, ',', '.').'</th>
                  </tr>';


            echo '<tr>
                    <td>'.'&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>';

            # Pegando a quantidade de fretes por motorista
            $qtdefrete = $consulta->PDOSelecionarPadrao("count(*) total", "TMOV",
                "CODCOLIGADA = 5
                             AND CODTMV = '2.2.46'
                             AND STATUS <> 'C'
                             AND CODVEN1 = '".$idmotorista."'
                             AND DATAEMISSAO BETWEEN '".$datainicial."' AND '".$datafinal."'");
            $dfrete = new ArrayIterator($qtdefrete);
            $valorfrete = $dfrete->current();

            #pegando os custos das contas do 1
            $sqlconta1 = $consulta->PDOSelecionarPadrao("sum(C.saldo) valor", "ZPortarePlanocontas A
                JOIN CCONTA B ON A.codconta = B.CODCONTA AND B.CODCOLIGADA IN (0,5)
                JOIN ZPortareViewContas C ON A.codconta like C.codconta+'%'", "A.codigo LIKE '1.%' AND C.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND C.chapa = '".$chapa."'");
            $dconta1 = new ArrayIterator($sqlconta1);
            $valorconta1 = $dconta1->current();
            echo '<tr>
                    <th></th>
                    <th>Ponto de Equilíbrio em R$ (Reais)</th>
                    <th class="direita">';
            $ptoequibilibrio = ($valorconta3->valor/($valorgrupo2->valor+$valorgrupo4->valor)) * $valorconta1->valor;
            echo number_format($ptoequibilibrio, 2, ',', '.');
            echo '  </th>
                  </tr>
                  <tr>
                    <th></th>
                    <th>Ponto de Equilíbrio em Qtde Frete</th>
                    <th class="direita">';
            $ptoequibilibriofrete = ($ptoequibilibrio*$valorfrete->total)/$valorconta1->valor;
            echo number_format($ptoequibilibriofrete, 2, ',', '.');
            echo '</th>
                  </tr>
                  <tr>
                    <th></th>
                    <th>Preço Médio do frete em R$ (Reais)</th>
                    <th class="direita">';
            $precomediofrete = $valorconta1->valor/$valorfrete->total;
            echo number_format($precomediofrete, 2, ',', '.');
            echo '</th>
                  </tr>
                  <tr>
                    <th></th>
                    <th>Qtde de Fretes Realizadas no Período</th>
                    <th class="direita">'.number_format($valorfrete->total, 0, ',', '.').'</th>
                  </tr>';

            ?>
        </table>
    </div>
</div>


<br>
<div class="text-center">
    <a href="javascript:window.print()" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Imprimir </a>
</div>
<br>

</body>
</html>