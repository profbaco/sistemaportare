<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$idgrupo = $_REQUEST['id'];
?>
<input type="hidden" id="idgrupo" value="<?php echo $idgrupo; ?>">

<script src="theme/default/js/functions.js"></script>

<table class="table table-hover table-condensed">
    <thead>
    <tr>
        <th>Código RM</th>
        <th>Código</th>
        <th>Descrição</th>
        <th>
            <button type="button" class="btn btn-info btn-xs" rel="tooltip" title="Novo Registro" onclick="abrirModalExtra('inserir', 0 , 'view/<?php echo $path; ?>/form.php')">
                <i class="glyphicon glyphicon-plus"></i>
            </button>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    $condicao = "A.idconta = B.id AND B.codconta = C.CODCONTA AND A.idgrupo = " . $idgrupo;

    $dados = $consulta->PDOSelecionar("A.id, B.codconta, B.codigo, C.descricao",
        "ZPortareDremontagem A, ZPortarePlanocontas B, CCONTA C", $condicao, 0, 300, "B.codigo");
    $d = new ArrayIterator($dados);
    while ($d->valid()){
        $m = $d->current();
        echo '<tr id="'.$path.$m->id.'">
                                <td>'.$m->codconta.'</td>
                                <td>'.$m->codigo.'</td>
                                <td>'.$m->descricao.'</td>
                                <td>
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