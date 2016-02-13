<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$idlogin = $_SESSION['idlogin'];

list($diai, $mesi, $anoi) = explode("/", $_REQUEST['periodoInicial']);
$periodoInicial = $anoi.'-'.$mesi.'-'.$diai;
list($diaf, $mesf, $anof) = explode("/", $_REQUEST['periodoFinal']);
$periodoFinal = $anof.'-'.$mesf.'-'.$diaf;

if ($periodoInicial > $periodoFinal) {
    echo '<br><b>O Período Inicial não pode ser maior do que o Período Final!</b>';
    exit;
}

$sqlconta = $consulta->PDOSelecionarPadrao("*", "ZPortarePlanocontas", "LEN(codconta)>15");// and codconta = '3.1.10.30.010.001'");
$d = new ArrayIterator($sqlconta);

while ($d->valid()){
    $valorconta = $d->current();

    $sqlsaldo = $consulta->PDOSelecionarPadrao("(SUM(CREDITO) - SUM(DEBITO)) SALDO", "(
                                                SELECT SUM(VALOR) CREDITO, 0 DEBITO
                                                FROM CPARTIDA
                                                WHERE CODCOLIGADA = 5
                                                AND CREDITO like '".$valorconta->codconta."%'
                                                AND DATA BETWEEN '".$periodoInicial."' AND '".$periodoFinal."'
                                                UNION
                                                SELECT 0 CREDITO, SUM(VALOR) DEBITO
                                                FROM CPARTIDA
                                                WHERE CODCOLIGADA = 5
                                                AND DEBITO like '".$valorconta->codconta."%'
                                                AND DATA BETWEEN '".$periodoInicial."' AND '".$periodoFinal."'
                                              ) AS TABELA", "1=1");
    $dsaldo = new ArrayIterator($sqlsaldo);
    $valorsaldo = $dsaldo->current();

    $consulta->PDOEditar("ZPortarePlanocontas", "saldo = '".$valorsaldo->SALDO."'", "id = ".$valorconta->id);

    $d->next();
}


# Atualizando saldo dos grupos de contas
$totaldogrupo = 0;

$grupo = $consulta->PDOSelecionarPadrao("*", "ZPortareDregrupo", "1=1");
$dgrupo = new ArrayIterator($grupo);

while ($dgrupo->valid()){
    $valorgrupo = $dgrupo->current();

    $saldogrupo = $consulta->PDOSelecionarPadrao("ISNULL(sum(b.saldo), 0) valor",
        "ZPortareDremontagem a, ZPortarePlanocontas b",
        "a.idconta = b.id and a.idgrupo = ".$valorgrupo->id);
    $dsaldogrupo = new ArrayIterator($saldogrupo);
    $valorsaldogrupo = $dsaldogrupo->current();

    #$totaldogrupo = $totaldogrupo + $valorsaldogrupo->valor;

    $consulta->PDOEditar("ZPortareDregrupo", "valor = ".$valorsaldogrupo->valor, "id = " . $valorgrupo->id);

    $dgrupo->next();
}

$consulta->PDOEditar("ZPortareDataBase", "datainicial='".$periodoInicial."', datafinal='".$periodoFinal."', idusuario = ".$idlogin.", datacriacao = getdate()", "id=1");



echo '<br><b>Contas atualizadas com sucesso!</b>';

