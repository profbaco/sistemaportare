<?php
if(!isset($_SESSION)) session_start();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$id = $_REQUEST['id'];
$tipo = $_REQUEST['tipo'];


if ($id>0){
    if(!isset($_SESSION)) session_start();
    include_once('../../dados/config.php');
    require_once('../../dados/classes.php');
    $consulta = new banco();

    $dados = $consulta->PDOSelecionar("*", "ZPortareCombustivel", "id = " . $id, 0, 1, "descricao");
    $d = new ArrayIterator($dados);
    $valor = $d->current();
}
?>
<script src="theme/default/js/functions.js"></script>

<input type="hidden" id="id" value="<?php echo $id; ?>">
<input type="hidden" id="acao" value="<?php echo $acao; ?>">


<div class="col-md-12">
    <label for="descricao">Descrição</label>
    <input type="text" id="descricao" class="form-control" placeholder="Descrição" value="<?php echo $valor->descricao; ?>">
</div>


<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="button" class="btn btn-success actions" onclick="salvarCombustivel()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
    </button>
</div>
<br> &nbsp;