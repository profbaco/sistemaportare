<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$idkm = $_REQUEST['idkm'];
$idcliente = $_REQUEST['idcliente'];
?>
<input type="hidden" id="idkm" value="<?php echo $idkm; ?>">
<input type="hidden" id="idcliente" value="<?php echo $idcliente; ?>">

<script src="theme/default/js/functions.js"></script>

<table class="table table-hover table-condensed" id="tabelaDepAdic">
    <thead>
    <tr>
        <th>Despesa</th>
        <th>Calculado</th>
        <th>
                <button type="button" class="btn btn-info btn-xs" rel="tooltip" title="Novo Registro" onclick="abrirModalExtra('inserirdespesa', 0 , 'view/<?php echo $path; ?>/formdespesas.php')">
                    <i class="glyphicon glyphicon-plus"></i>
                </button>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    $dados = $consulta->PDOSelecionar("A.id,
                                        CASE WHEN A.agregado = 0 THEN A.idcaminhao ELSE '**'+A.idcaminhao END idcaminhao,
                                        B.descricao, A.calculado", "ZPortareKmDespesas A, ZPortareDespesas B",
        "A.iddespesa = B.id AND A.idkm = ".$idkm." AND A.agregado in (4) AND A.idcliente = '".$idcliente."'",
        0, 600, "idcaminhao DESC");
    $d = new ArrayIterator($dados);
    while ($d->valid()){
        $m = $d->current();
        echo '<tr id="'.$path.$m->id.'">
                                <td>'.$m->descricao.'</td>
                                <td>'.number_format($m->calculado,2,',','.').'</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-danger btn-xs" rel="tooltip" title="Editar" onclick="abrirModalExtra(\'editar\', '.$m->id.' , \'view/'.$path.'/form.php\')">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-xs" rel="tooltip" title="Excluir" onclick="modalExcluir(\'view/'.$path.'/actions.php?quem=desp\', '.$m->id.')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
                                </td>
                              </tr>';
        $d->next();
    }
    ?>
    </tbody>
</table>