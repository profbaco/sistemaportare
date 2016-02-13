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

    $dados = $consulta->PDOSelecionar("*", "ZPortarePerfil", "id = " . $id, 0, 1, "nome");
    $d = new ArrayIterator($dados);
    $valor = $d->current();
}
?>
<script src="theme/default/js/functions.js"></script>

<input type="hidden" id="id" value="<?php echo $id; ?>">
<input type="hidden" id="acao" value="<?php echo $acao; ?>">


<div class="col-md-12">
    <label for="nome">Nome do Perfil</label>
    <input type="text" id="nome" class="form-control" placeholder="Nome do Perfil" value="<?php echo $valor->nome; ?>">
</div>


<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="button" class="btn btn-success actions" onclick="salvarPerfil()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
    </button>
</div>
<br> &nbsp;