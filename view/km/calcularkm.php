<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$idkm = $_REQUEST['idkm'];

# Agregados do KM
$sqlagregado = $consulta->PDOSelecionar("isnull(sum(calculado), 0) calculado, idkm", "ZPortareKmDespesas", "idkm=".$idkm." and agregado<4 group by idkm", 0, 10, "idkm");
$dagregado = new ArrayIterator($sqlagregado);
$valoragregado = $dagregado->current();

# Buscando os valores dos clientes
$sqlclientes = $consulta->PDOSelecionar("idkm, idcliente, diasviagem, isnull(kmchao+kmasfalto,0) km", "ZPortareKmClientes", "idkm=".$idkm,0,10,"idkm");
$d = new ArrayIterator($sqlclientes);
while ($d->valid()){
    $m = $d->current();
    # Pegandos os gastos deste cliente
    $sqlagregadoclientes = $consulta->PDOSelecionar("isnull(sum(calculado), 0) calculado, idkm", "ZPortareKmDespesas", "idkm=".$idkm." and idcliente='".$m->idcliente."' group by idkm", 0, 10, "idkm");
    $dcliagregado = new ArrayIterator($sqlagregadoclientes);
    $valorcliente = $dcliagregado->current();

    # Calculando os valores do dia
    $valordias = (($valoragregado->calculado + $valorcliente->calculado) * $m->diasviagem);
    # Valor do KM
    if ($m->km == 0) $km = 1;
    else $km = ($m->km * 2);
    # Valor do KM
    $valorkm = $valordias/$km;
    # Atualizando o valor do KM
    $consulta->PDOEditar("ZPortareKmClientes", "valorkm = ".$valorkm, "idkm = ".$idkm." and idcliente = '".$m->idcliente."'");

    $d->next();
}

echo 'Cálculo realizado com sucesso!';