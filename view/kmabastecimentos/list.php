<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$idkm = $_REQUEST['id'];
$tipo = $_REQUEST['tipo'];
?>
<input name="idkm" id="idkm" type="hidden" value="<?php echo $idkm; ?>"/>

<script src="theme/default/js/functions.js"></script>

<table class="table table-hover table-condensed">
    <thead>
        <tr>
            <th>Data</th>
            <th>Litragem</th>
            <th>Valor Total</th>
            <th>Km</th>
            <th>Km Rodado</th>
            <th>Média</th>
            <th>Tipo</th>
            <th>
                <button type="button" class="btn btn-info btn-xs" rel="tooltip" title="Novo Registro" onclick="abrirModalExtra('inserir', 0 , 'view/kmabastecimentos/form.php?idkm=<?php echo $idkm; ?>')">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php
        $sql = $consulta->PDOSelecionarPadrao("A.id, CONVERT(VARCHAR(15), A.data, 103) dtabastecimento, A.litragem, A.valor, A.km, A.data,
CASE A.tipo
	WHEN 'S' THEN 'Saída'
	WHEN 'A' THEN 'Abastecimentos'
	WHEN 'C' THEN 'Chegada'
END status", "ZPortareKmAbastecimentos A", "A.idkm = " . $idkm . " ORDER BY A.data, A.km");
        $d = new ArrayIterator($sql);
        $kmanterior = 0;
        while ($d->valid()) {
            $valor = $d->current();
            if ($kmanterior==0) $kmanterior = $valor->km;
            echo '<tr id="'.$path.$valor->id.'">
                    <td>'.$valor->dtabastecimento.'</td>
                    <td>'.number_format($valor->litragem,2,',','.').'</td>
                    <td>'.number_format($valor->valor,2,',','.').'</td>
                    <td>'.number_format($valor->km,0,',','.').'</td>
                    <td>'.number_format($valor->km - $kmanterior,0,',','.').'</td>
                    <td>'.number_format(($valor->km - $kmanterior)/$valor->litragem,2,',','.').'</td>
                    <td>'.$valor->status.'</td>
                    <td>
                        <a href="javascript:void(0)" class="btn btn-success btn-xs" rel="tooltip" title="Editar" onclick="abrirModalExtra(\'editar\', '.$valor->id.' , \'view/kmabastecimentos/form.php?idkm='.$idkm.'\')">
                            <i class="glyphicon glyphicon-edit"></i>
                        </a>
                        <a href="javascript:void(0)" class="btn btn-primary btn-xs" rel="tooltip" title="Excluir" onclick="modalExcluir(\'view/kmabastecimentos/actions.php\', '.$valor->id.')">
                            <i class="glyphicon glyphicon-trash"></i>
                        </a>
                    </td>
                  </tr>';
            $kmanterior = $valor->km;
            $d->next();
        }
    ?>
    </tbody>
</table>
