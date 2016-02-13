<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$id = $_REQUEST['id'];

if ($id>0){
    $dados = $consulta->PDOSelecionar("*", "ZPortareDregrupo", "id = " . $id, 0, 1, "id");
    $d = new ArrayIterator($dados);
    $valor = $d->current();
}
?>
<input type="hidden" id="id" value="<?php echo $id; ?>">
<input type="hidden" id="acao" value="<?php echo $acao; ?>">

<div class="col-md-12">
    <div class="row">
        <div class="col-md-3">
            <label for="codigo">Código</label>
            <input type="text" id="codigo" class="form-control" placeholder="Código da Conta" value="<?php echo $valor->codigo; ?>" autofocus="true">
        </div>
        <div class="col-md-9">
            <label for="descricao">Descrição</label>
            <input type="text" id="descricao" class="form-control" placeholder="Descrição da Conta" value="<?php echo $valor->descricao; ?>">
        </div>
    </div>
</div>

<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="button" class="btn btn-success actions" onclick="salvarGrupoDre()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
    </button>
</div> &nbsp;
<br> &nbsp;

<script src="theme/default/js/functions.js"></script>