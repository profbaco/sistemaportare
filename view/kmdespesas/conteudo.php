<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$idkm = $_REQUEST['idkm'];
$agregado = $_REQUEST['agregado'];

if ($agregado>"1") {
?>
<table class="table table-hover table-condensed" id="tabelaDepAdic">
    <thead>
    <tr>
        <th>Despesa</th>
        <th>Calculado</th>
        <th><?php if ($agregado>="3"){ ?>
            <button type="button" class="btn btn-info btn-xs" rel="tooltip" title="Novo Registro" onclick="abrirModalExtra('inserir', 0 , 'view/<?php echo $path; ?>/form.php?agregado=<?php echo $agregado; ?>')">
                <i class="glyphicon glyphicon-plus"></i>
            </button>
            <?php } ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    $dados = $consulta->PDOSelecionar("A.id,
                                        CASE WHEN A.agregado = 0 THEN A.idcaminhao ELSE '**'+A.idcaminhao END idcaminhao,
                                        B.descricao, A.calculado", "ZPortareKmDespesas A, ZPortareDespesas B",
        "A.iddespesa = B.id AND A.idkm = ".$idkm." AND A.agregado in (".$agregado.")",
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
                                    <a href="javascript:void(0)" class="btn btn-primary btn-xs" rel="tooltip" title="Excluir" onclick="modalExcluir(\'view/'.$path.'/actions.php\', '.$m->id.')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
                                </td>
                              </tr>';
        $d->next();
    }
    ?>
    </tbody>
</table>


<?php
} else {
?>
    <table class="table table-hover table-condensed">
        <thead>
        <tr>
            <th>Caminhão</th>
            <th>Despesa</th>
            <th>Calculado</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $dados = $consulta->PDOSelecionar("A.id,
                                        CASE WHEN A.agregado = 0 THEN A.idcaminhao ELSE '**'+A.idcaminhao END idcaminhao,
                                        B.descricao, A.calculado", "ZPortareKmDespesas A, ZPortareDespesas B",
            "A.iddespesa = B.id AND A.idkm = ".$idkm." AND A.agregado in (0,1)",
            0, 600, "idcaminhao DESC");
        $d = new ArrayIterator($dados);
        while ($d->valid()){
            $m = $d->current();
            echo '<tr id="'.$path.$m->id.'">
                                <td>'.$m->idcaminhao.'</td>
                                <td>'.$m->descricao.'</td>
                                <td>'.number_format($m->calculado,2,',','.').'</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-danger btn-xs" rel="tooltip" title="Editar" onclick="abrirModalExtra(\'editar\', '.$m->id.' , \'view/'.$path.'/form.php\')">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-xs" rel="tooltip" title="Excluir" onclick="modalExcluir(\'view/'.$path.'/actions.php\', '.$m->id.')">
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </a>
                                </td>
                              </tr>';
            $d->next();
        }
        ?>
        </tbody>
    </table>

<?php } ?>