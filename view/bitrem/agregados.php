<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$tipo = $_REQUEST['tipo'];
$placa = $_REQUEST['placa'];


    $sql = $consulta->PDOSelecionar("IDOBJOFPAI,
			CONVERT(VARCHAR(30), DATAENTRADA, 103) + ' - ' +CONVERT(VARCHAR(30), DATAENTRADA, 108) DTHRENTRADA,
			CONVERT(VARCHAR(30), DATASAIDA, 103) + ' - ' +CONVERT(VARCHAR(30), DATASAIDA, 108) DTHRSAIDA,
			RECMODIFIEDBY, DATASAIDA", "OFHISTOBJFILHO (NOLOCK)",
            "IDOBJOF = '".$placa."'", 0, 500, "DATASAIDA ASC");
    $d = new ArrayIterator($sql);

    echo '<table class="table table-hover table-condensed">
				<thead>
				<tr>
					<th>Placa</th>
					<th>Dt/Hr Entrada</th>
					<th>Dt/Hr Saída</th>
					<th>Usuário RM</th>
				</tr>
				</thead>';
    while ($d->valid()) {
        $valor = $d->current();
        echo '<tr>
				<td>'.$valor->IDOBJOFPAI.'</td>
				<td>'.$valor->DTHRENTRADA.'</td>
				<td>'.$valor->DTHRSAIDA.'</td>
				<td>'.$valor->RECMODIFIEDBY.'</td>
			  </tr>';
        $d->next();
    }
    echo '</table>';