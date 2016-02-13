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

$sqlperiodo = $consulta->PDOSelecionarPadrao("CONVERT(VARCHAR(15), datainicial, 103) dtinicial, datainicial, CONVERT(VARCHAR(15), datafinal, 103) dtfinal, datafinal", "ZPortareDataBase", "id=1");
$dperiodo = new ArrayIterator($sqlperiodo);
$valorperiodo = $dperiodo->current();

$datainicial = $valorperiodo->datainicial;
$datafinal = $valorperiodo->datafinal;

$placa = $_REQUEST['placa'];
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4><strong>
                Relatório de Ponto de Equilíbrio - Por Placa: </strong><?php
                echo $placa;
                $ccusto = $consulta->PDOSelecionarPadrao("NOME", "GCCUSTO", "CODCCUSTO = '".$placa."' AND CODCOLIGADA = 5");
                $dccusto = new ArrayIterator($ccusto);
                $valorccusto = $dccusto->current();
                echo ' .:. '.$valorccusto->NOME;
                ?>

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
                "D.data BETWEEN '".$datainicial."' AND '".$datafinal."'
                 AND D.codccusto = '".$placa."'
                 AND A.id = 1
                 GROUP BY A.codigo, A.descricao");
            $dgrupo2 = new ArrayIterator($sqlgrupo2);
            $valorgrupo2 = $dgrupo2->current();
            echo '<tr>
                    <th>'.$valorgrupo2->codigo.'</th>
                    <th>'.$valorgrupo2->descricao.'</th>
                    <th class="direita">'.number_format($valorgrupo2->valor, 2, ',', '.').'</th>
                  </tr>';
            # Descrição dos valores do GRUPO 2
            echo '<tr><td colspan="3">';
            $sqldescgrupo2 = $consulta->PDOSelecionarPadrao("D.codconta, D.codigo, E.descricao, SUM(D.saldo) saldo",
                        "ZPortareDregrupo A
                JOIN ZPortareDremontagem B ON A.id = B.idgrupo
                JOIN ZPortarePlanocontas C ON C.id = B.idconta
                JOIN ZPortareViewContas D ON D.codconta = C.codconta AND D.codigo = C.codigo
                JOIN CCONTA E ON E.CODCONTA = D.codconta",
                "D.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND D.codccusto = '".$placa."' AND A.id = 1
                 GROUP BY D.codconta, E.descricao, D.codigo");
            echo '<table class="table table-condensed table-responsive">
                    <thead>
                    <tr>
                        <th>Conta RM</th>
                        <th>Conta Portare</th>
                        <th>Descrição</th>
                        <th class="direita">Valor</th>
                    </tr>
                    </thead>
                    <tbody>';
            $descgrupo2 = new ArrayIterator($sqldescgrupo2);
            while($descgrupo2->valid()){
                $valordescgrupo2 = $descgrupo2->current();
                echo '<tr>
                        <td>'.$valordescgrupo2->codconta.'</td>
                        <td>'.$valordescgrupo2->codigo.'</td>
                        <td>'.$valordescgrupo2->descricao.'</td>
                        <td class="direita">'.number_format($valordescgrupo2->saldo, 2, ',', '.').'</td>
                      </tr>';
                $descgrupo2->next();
            }
            echo '</tbody></table>';

            echo '</td><tr>';



            #pegando os custos das contas do 3
            $sqlconta3 = $consulta->PDOSelecionarPadrao("SUM(C.saldo) valor", "ZPortarePlanocontas B
	            JOIN ZPortareViewContas C ON B.codconta = C.codconta AND B.codigo = C.codigo",
                "C.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND B.codigo like '3.%' AND C.codccusto = '".$placa."'");
            $dconta3 = new ArrayIterator($sqlconta3);
            $valorconta3 = $dconta3->current();
            echo '<tr>
                    <td>3</td>
                    <td>CUSTOS VARIÁVEIS DA FROTA</td>
                    <td class="direita">'.number_format($valorconta3->valor, 2, ',', '.').'</td>
                  </tr>';
            # Descrição dos custos das contas do 3
            echo '<tr><td colspan="3">';
            $sqldescgrupo3 = $consulta->PDOSelecionarPadrao("D.codconta, D.codigo, E.descricao, SUM(D.saldo) saldo",
                "ZPortarePlanocontas C
                JOIN ZPortareViewContas D ON D.codconta = C.codconta AND D.codigo = C.codigo
                JOIN CCONTA E ON E.CODCONTA = D.codconta",
                "D.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND C.codigo like '3.%' AND D.codccusto = '".$placa."'
                 GROUP BY D.codconta, E.descricao, D.codigo");
            echo '<table class="table table-condensed table-responsive">
                    <thead>
                    <tr>
                        <th>Conta RM</th>
                        <th>Conta Portare</th>
                        <th>Descrição</th>
                        <th class="direita">Valor</th>
                    </tr>
                    </thead>
                    <tbody>';
            $descgrupo3 = new ArrayIterator($sqldescgrupo3);
            while($descgrupo3->valid()){
                $valordescgrupo3 = $descgrupo3->current();
                echo '<tr>
                        <td>'.$valordescgrupo3->codconta.'</td>
                        <td>'.$valordescgrupo3->codigo.'</td>
                        <td>'.$valordescgrupo3->descricao.'</td>
                        <td class="direita">'.number_format($valordescgrupo3->saldo, 2, ',', '.').'</td>
                      </tr>';
                $descgrupo3->next();
            }
            echo '</tbody></table>';

            echo '</td><tr>';




            #pegando os dados do grupo 4
            $sqlgrupo4 = $consulta->PDOSelecionarPadrao("A.codigo, A.descricao, SUM(D.saldo) valor", "ZPortareDregrupo A
                  JOIN ZPortareDremontagem B ON A.id = B.idgrupo
                  JOIN ZPortarePlanocontas C ON C.id = B.idconta
                  JOIN ZPortareViewContas D ON D.codconta = C.codconta AND D.codigo = C.codigo",
                "D.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND D.codccusto = '".$placa."' AND A.id = 3 GROUP BY A.codigo, A.descricao");
            $dgrupo4 = new ArrayIterator($sqlgrupo4);
            $valorgrupo4 = $dgrupo4->current();
            echo '<tr>
                    <th>'.$valorgrupo4->codigo.'</th>
                    <th>'.$valorgrupo4->descricao.'</th>
                    <th class="direita">'.number_format(($valorgrupo2->valor+$valorgrupo4->valor), 2, ',', '.').'</th>
                  </tr>';
            /*
            # Descrição dos custos do grupo 4
            echo '<tr><td colspan="3">';
            $sqldescgrupo4 = $consulta->PDOSelecionarPadrao("D.codconta, D.codigo, E.descricao, SUM(D.saldo) saldo",
                "ZPortareDregrupo A
                JOIN ZPortareDremontagem B ON A.id = B.idgrupo
                JOIN ZPortarePlanocontas C ON C.id = B.idconta
                JOIN ZPortareViewContas D ON D.codconta = C.codconta AND D.codigo = C.codigo
                JOIN CCONTA E ON E.CODCONTA = D.codconta",
                "D.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND D.codccusto = '".$placa."' AND A.id = 3
                 GROUP BY D.codconta, E.descricao, D.codigo");
            echo '<table class="table table-condensed table-responsive">
                    <thead>
                    <tr>
                        <th>Conta RM</th>
                        <th>Conta Portare</th>
                        <th>Descrição</th>
                        <th class="direita">Valor</th>
                    </tr>
                    </thead>
                    <tbody>';
            $descgrupo4 = new ArrayIterator($sqldescgrupo4);
            while($descgrupo4->valid()){
                $valordescgrupo4 = $descgrupo4->current();
                echo '<tr>
                        <td>'.$valordescgrupo4->codconta.'</td>
                        <td>'.$valordescgrupo4->codigo.'</td>
                        <td>'.$valordescgrupo4->descricao.'</td>
                        <td class="direita">'.number_format($valordescgrupo4->saldo, 2, ',', '.').'</td>
                      </tr>';
                $descgrupo4->next();
            }
            echo '<tr>
                        <td></td>
                        <td></td>
                        <td>Receita Líquida de Vendas</td>
                        <td class="direita">'.number_format($valorgrupo2->valor, 2, ',', '.').'</td>
                      </tr>';
            echo '</tbody></table>';

            echo '</td><tr>';*/
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
                $saldoconta = $consulta->PDOSelecionarPadrao("sum(saldo) saldo", "ZPortareViewContas",
                    "data BETWEEN '".$datainicial."'
                     AND '".$datafinal."'
                     AND codigo like '".$valorcontas->codigo."%'
                     AND codccusto = '".$placa."'");
                $dsaldo = new ArrayIterator($saldoconta);
                $valorsaldo = $dsaldo->current();
                echo '<tr>
                    <td>'.$valorcontas->codigo.'</td>
                    <td>'.$valorcontas->descricao.'</td>
                    <td class="direita">'.number_format($valorsaldo->saldo, 2, ',', '.').'</td>
                  </tr>';

                # Descrição dos contas do plano de contas
                echo '<tr><td colspan="3">';
                $sqldescgrupo3 = $consulta->PDOSelecionarPadrao("D.codconta, D.codigo, E.descricao, SUM(D.saldo) saldo",
                    "ZPortarePlanocontas C
                JOIN ZPortareViewContas D ON D.codconta = C.codconta AND D.codigo = C.codigo
                JOIN CCONTA E ON E.CODCONTA = D.codconta",
                    "D.data BETWEEN '".$datainicial."'
                     AND '".$datafinal."'
                     AND D.codigo like '".$valorcontas->codigo."%'
                     and D.codccusto = '".$placa."'
                 GROUP BY D.codconta, E.descricao, D.codigo");
                echo '<table class="table table-condensed table-responsive">
                    <thead>
                    <tr>
                        <th>Conta RM</th>
                        <th>Conta Portare</th>
                        <th>Descrição</th>
                        <th class="direita">Valor</th>
                    </tr>
                    </thead>
                    <tbody>';
                $descgrupo3 = new ArrayIterator($sqldescgrupo3);
                while($descgrupo3->valid()){
                    $valordescgrupo3 = $descgrupo3->current();
                    echo '<tr>
                        <td>'.$valordescgrupo3->codconta.'</td>
                        <td>'.$valordescgrupo3->codigo.'</td>
                        <td>'.$valordescgrupo3->descricao.'</td>
                        <td class="direita">'.number_format($valordescgrupo3->saldo, 2, ',', '.').'</td>
                      </tr>';
                    $descgrupo3->next();
                }
                echo '</tbody></table>';

                echo '</td><tr>';


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
                WHERE D.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND D.codccusto = '".$placa."' AND A.id < 6) valor", "ZPortareDregrupo", "codigo = 21");
            $dgrupo21 = new ArrayIterator($sqlgrupo21);
            $valorgrupo21 = $dgrupo21->current();
            echo '<tr>
                    <th>'.$valorgrupo21->codigo.'</th>
                    <th>'.$valorgrupo21->descricao.'</th>
                    <th class="direita">'.number_format($valorgrupo21->valor, 2, ',', '.').'</th>
                  </tr>';

            /*# Descrição dos custos do grupo 21
            echo '<tr><td colspan="3">';
            $sqldescgrupo21 = $consulta->PDOSelecionarPadrao("D.codconta, D.codigo, E.descricao, SUM(D.saldo) saldo",
                "ZPortareDregrupo A
                JOIN ZPortareDremontagem B ON A.id = B.idgrupo
                JOIN ZPortarePlanocontas C ON C.id = B.idconta
                JOIN ZPortareViewContas D ON D.codconta = C.codconta AND D.codigo = C.codigo
                JOIN CCONTA E ON E.CODCONTA = D.codconta",
                "D.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND D.codccusto = '".$placa."' AND A.codigo=21
                 GROUP BY D.codconta, E.descricao, D.codigo");
            echo '<table class="table table-condensed table-responsive">
                    <thead>
                    <tr>
                        <th>Conta RM</th>
                        <th>Conta Portare</th>
                        <th>Descrição</th>
                        <th class="direita">Valor</th>
                    </tr>
                    </thead>
                    <tbody>';
            $descgrupo21 = new ArrayIterator($sqldescgrupo21);
            while($descgrupo21->valid()){
                $valordescgrupo21 = $descgrupo21->current();
                echo '<tr>
                        <td>'.$valordescgrupo21->codconta.'</td>
                        <td>'.$valordescgrupo21->codigo.'</td>
                        <td>'.$valordescgrupo21->descricao.'</td>
                        <td class="direita">'.number_format($valordescgrupo21->saldo, 2, ',', '.').'</td>
                      </tr>';
                $descgrupo21->next();
            }
            echo '</tbody></table>';

            echo '</td><tr>';*/









            echo '<tr>
                    <td>'.'&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>';

            # Pegando a quantidade de fretes
            $qtdefrete = $consulta->PDOSelecionarPadrao("count(*) total", "TMOV",
                "CODCOLIGADA = 5
                             AND CODTMV IN ('2.2.41', '2.2.46', '2.2.50')
                             AND STATUS <> 'C'
                             AND CODCCUSTO = '".$placa."'
                             AND DATAEMISSAO BETWEEN '".$datainicial."' AND '".$datafinal."'");
            $dfrete = new ArrayIterator($qtdefrete);
            $valorfrete = $dfrete->current();

            #pegando os custos das contas do 1
            $sqlconta1 = $consulta->PDOSelecionarPadrao("sum(C.saldo) valor", "ZPortarePlanocontas A
                JOIN CCONTA B ON A.codconta = B.CODCONTA AND B.CODCOLIGADA IN (0,5)
                JOIN ZPortareViewContas C ON A.codconta like C.codconta+'%'", "A.codigo LIKE '1.%' AND C.data BETWEEN '".$datainicial."' AND '".$datafinal."' AND C.codccusto = '".$placa."'");
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
    <div class="panel-footer">
        <div class="text-center">
            <a href="javascript:window.print()" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span> Imprimir </a>
        </div>
    </div>
</div>


</body>
</html>