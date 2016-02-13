<?php
if(!isset($_SESSION)) session_start();
$nomelogin = $_SESSION['nomelogin'];
$idlogin = $_SESSION['idlogin'];

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$id = $_REQUEST['id'];

$dados = $consulta->PDOSelecionar("status, id", "ZPortareKm", "id = " . $id, 0, 1, "id");
$d = new ArrayIterator($dados);
$valor = $d->current();
?>
<script src="theme/default/js/functions.js"></script>

<input type="hidden" id="id" value="<?php echo $id; ?>">

<label for="status">Selecionar novo Status</label>
<select id="status" class="form-control">
    <option value="0" <?php if ($valor->status==0) echo "selected"; ?>>Montagem</option>
    <option value="1" <?php if ($valor->status==1) echo "selected"; ?>>Aguardando Resposta</option>
    <option value="2" <?php if ($valor->status==2) echo "selected"; ?>>Aprovado</option>
    <option value="3" <?php if ($valor->status==3) echo "selected"; ?>>Em Viagem</option>
    <option value="4" <?php if ($valor->status==4) echo "selected"; ?>>Concluído</option>
    <option value="5" <?php if ($valor->status==5) echo "selected"; ?>>Cancelado</option>
    <option value="6" <?php if ($valor->status==6) echo "selected"; ?>>Em Carregamento</option>
</select>

<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="button" class="btn btn-success actions" onclick="alterarStatus()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
    </button>
</div> &nbsp;
<br> &nbsp;