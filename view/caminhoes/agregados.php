<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$tipo = $_REQUEST['tipo'];
$placa = $_REQUEST['placa'];


if ($tipo == 'caminhoes') {
    $sql = $consulta->PDOSelecionar("IDOBJOF, CONVERT(VARCHAR(30), DATAENTRADA, 103) + ' - ' +CONVERT(VARCHAR(30), DATAENTRADA, 108) DTHRENTRADA, RECMODIFIEDBY",
        "OFHISTOBJFILHO (NOLOCK)", "DATASAIDA IS NULL AND IDOBJOFPAI = '".$placa."'", 0, 500, "IDOBJOF");
    $d = new ArrayIterator($sql);
    echo '<table class="table table-hover table-condensed">
				<thead>
				<tr>
					<th>Placa</th>
					<th>Dt Entrada</th>
					<th>Usuário RM</th>
				</tr>
				</thead>';
    while ($d->valid()){
        $valor = $d->current();
        echo '<tr>
				<td>'.$valor->IDOBJOF.'</td>
				<td>'.$valor->DTHRENTRADA.'</td>
				<td>'.$valor->RECMODIFIEDBY.'</td>
			  </tr>';
        $d->next();
    }
    echo '</table>';

}/* else {
    $sentenca = "SELECT IDOBJOFPAI,
			CONVERT(VARCHAR(30), DATAENTRADA, 103) + ' - ' +CONVERT(VARCHAR(30), DATAENTRADA, 108) DTHRENTRADA,
			CONVERT(VARCHAR(30), DATASAIDA, 103) + ' - ' +CONVERT(VARCHAR(30), DATASAIDA, 108) DTHRSAIDA,
			RECMODIFIEDBY, 1 ORDEM FROM OFHISTOBJFILHO (NOLOCK)
			WHERE IDOBJOF = '".$placa."'
			AND DATASAIDA IS NULL
			
			UNION
			
			SELECT IDOBJOFPAI,
			CONVERT(VARCHAR(30), DATAENTRADA, 103) + ' - ' +CONVERT(VARCHAR(30), DATAENTRADA, 108) DTHRENTRADA,
			CONVERT(VARCHAR(30), DATASAIDA, 103) + ' - ' +CONVERT(VARCHAR(30), DATASAIDA, 108) DTHRSAIDA,
			RECMODIFIEDBY, 2 ORDEM FROM OFHISTOBJFILHO (NOLOCK)
			WHERE IDOBJOF = '".$placa."'
			AND DATASAIDA IS NOT NULL
			ORDER BY ORDEM ASC, DTHRSAIDA DESC";
    $sql = sqlsrv_query($connsql, $sentenca);

    echo '<table class="table table-hover table-condensed">
				<tr>
					<td>Placa</td>
					<td>Dt/Hr Entrada</td>
					<td>Dt/Hr Saída</td>
					<td>Usuário RM</td>
				</tr>';
    while ($valor = sqlsrv_fetch_object($sql)) {
        echo '<tr>
				<td>'.$valor->IDOBJOFPAI.'</td>
				<td>'.$valor->DTHRENTRADA.'</td>
				<td>'.$valor->DTHRSAIDA.'</td>
				<td>'.$valor->RECMODIFIEDBY.'</td>
			  </tr>';
    }
    echo '</table>';
}*/