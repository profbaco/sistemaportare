<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$query = strtolower($_REQUEST["term"]);
$quem = $_REQUEST['quem'];
$codfilial = $_REQUEST['codfilial'];

/*if ($quem=="idmotorista"){
    $sql = $consulta->PDOSelecionar("chapa id, nome, (chapa+' - '+nome) text", "pfunc", "codcoligada = 5 and (nome LIKE '".$query."%' or chapa like '".$query."%')", 0, 100,"nome"); 

} else*/if($quem=="codconta") {
    $sql = $consulta->PDOSelecionar("CODCONTA id, CODCONTA+' - '+DESCRICAO text, CODCONTA, DESCRICAO",
        "CCONTA", "CODCOLIGADA IN (0, 5) AND INATIVA = 0 AND (DESCRICAO like '".$query."%' OR CODCONTA like '".$query."%')",
        0, 100,"CODCONTA");

} elseif($quem=="iddespesa") {
    $sql = $consulta->PDOSelecionar("id, descricao text, descricao",
        "ZPortareDespesas", "tipo = 'v' and descricao like '".$query."%'", 0, 100, "descricao");

} elseif($quem=="idcaminhao") {
    $sql = $consulta->PDOSelecionar("PLACA id, PLACA text, PLACA",
        "ZPortalCAMINHOES", "PLACA like '".$query."%'", 0, 100, "PLACA");

} elseif($quem=="idmotorista") {
    $sql = $consulta->PDOSelecionar("CHAPA id, (CHAPA+' - '+NOME) text, NOME", "PFUNC",
        "CODFUNCAO IN ('003', '018', '019', '099') AND CODSITUACAO <> 'D' AND CODCOLIGADA = 5 AND
         (CHAPA like '".$query."%' OR NOME LIKE '".$query."%')", 0, 100, "NOME");

} elseif($quem=="idcliente") {
    $sql = $consulta->PDOSelecionar("CODCFO id, (NOME+' - '+CGCCFO) text, ISNULL(VALOROP1, 0) ASFALTO, ISNULL(VALOROP2, 0) CHAO, NOME", "FCFO",
        "CODCOLIGADA in (0,5) AND ATIVO=1 AND (CGCCFO like '".$query."%' OR NOME LIKE '".$query."%')", 0, 100, "NOME");

} elseif($quem=="idcombustivel") {
    $sql = $consulta->PDOSelecionar("id, descricao text, descricao",
        "ZPortareCombustivel", "descricao like '".$query."%'", 0, 100, "descricao");

} elseif($quem=="idconta") {
    $sql = $consulta->PDOSelecionar("A.id, (A.codigo+' - '+B.descricao) text, A.codigo",
        "ZPortarePlanocontas A, CCONTA B", "A.codconta = B.codconta and (B.descricao like '".$query."%' or A.codigo like '".$query."%')", 0, 100, "A.codigo");

} elseif($quem=="codccusto") {
    $sql = $consulta->PDOSelecionarPadrao("CODCCUSTO id, (CODCCUSTO+' - '+NOME) text",
        "GCCUSTO", "CODCOLIGADA=5 AND ATIVO='T' AND (CODCCUSTO LIKE '".$query."%' OR NOME LIKE '".$query."%')");

} elseif($quem=="codtmv") {
    $sql = $consulta->PDOSelecionarPadrao("CODTMV id, (CODTMV+' - '+NOME) text",
        "TTMV", "CODCOLIGADA=5 AND CODTMV LIKE '2.1.%' AND (CODTMV LIKE '".$query."%' OR NOME LIKE '".$query."%')");

}elseif($quem=="codfilial") {
    $sql = $consulta->PDOSelecionarPadrao("CODFILIAL id, (CONVERT(VARCHAR(5),CODFILIAL)+' - '+NOMEFANTASIA) text",
        "GFILIAL", "CODCOLIGADA=5 AND (CODFILIAL LIKE '".$query."%' OR NOMEFANTASIA LIKE '".$query."%')");

}elseif($quem=="codloc") {
    $sql = $consulta->PDOSelecionarPadrao("CODLOC id, (CODLOC+' - '+NOME) text",
        "TLOC", "CODCOLIGADA = 5 AND CODFILIAL = ".$codfilial." AND (CODLOC LIKE '".$query."%' OR NOME LIKE '".$query."%')");

}elseif($quem=="codtb1flx") {
    $sql = $consulta->PDOSelecionarPadrao("CODTB1FLX id, (CODTB1FLX+' - '+DESCRICAO) text",
        "FTB1", "CODCOLIGADA = 5 AND ATIVO = 1 AND (CODTB1FLX LIKE '".$query."%' OR DESCRICAO LIKE '".$query."%') ORDER BY CODTB1FLX");

}elseif($quem=="codtb2flx") {
    $sql = $consulta->PDOSelecionarPadrao("CODTB2FLX id, (CODTB2FLX+' - '+DESCRICAO) text",
        "FTB2", "CODCOLIGADA = 5 AND ATIVO = 1 AND (CODTB2FLX LIKE '".$query."%' OR DESCRICAO LIKE '".$query."%') ORDER BY CODTB2FLX");

}elseif($quem=="codtb3flx") {
    $sql = $consulta->PDOSelecionarPadrao("CODTB3FLX id, (CODTB3FLX+' - '+DESCRICAO) text",
        "FTB3", "CODCOLIGADA = 5 AND ATIVO = 1 AND (CODTB3FLX LIKE '".$query."%' OR DESCRICAO LIKE '".$query."%') ORDER BY CODTB3FLX");

}elseif($quem=="codtb4flx") {
    $sql = $consulta->PDOSelecionarPadrao("CODTB4FLX id, (CODTB4FLX+' - '+DESCRICAO) text",
        "FTB4", "CODCOLIGADA = 5 AND ATIVO = 1 AND (CODTB4FLX LIKE '".$query."%' OR DESCRICAO LIKE '".$query."%') ORDER BY CODTB4FLX");

}elseif($quem=="codven1") {
    $sql = $consulta->PDOSelecionarPadrao("CODVEN id, (CODVEN+' - '+NOME) text",
        "TVEN", "CODCOLIGADA = 5 AND INATIVO = 0 AND (CODVEN LIKE '".$query."%' OR NOME LIKE '".$query."%') ORDER BY NOME");

}elseif($quem=="idprd") {
    $sql = $consulta->PDOSelecionarPadrao("IDPRD id, (CODIGOPRD+' - '+NOMEFANTASIA) text",
        "TPRD", "CODCOLIGADA = 5 AND INATIVO = 0 AND (CODIGOPRD LIKE '".$query."%' OR NOMEFANTASIA LIKE '".$query."%') ORDER BY CODIGOPRD");

}

$arr = array();

$d = new ArrayIterator($sql);
while ($d->valid()){
    $m = $d->current();
    $arr[] = $m;
    $d->next();
}
echo json_encode($arr);
