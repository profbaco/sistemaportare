<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$idkm = $_REQUEST['id'];
?>
<input type="hidden" id="idkm" value="<?php echo $idkm; ?>">

<script src="theme/default/js/functions.js"></script>

<div class="row">
    <div class="col-md-6" id="verClientes">

    </div>
    <div class="col-md-6" id="verDespesas">

    </div>
</div>

<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-default actions" onclick="calcularKm()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Calcular Km
    </button>
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Fechar
    </button>
</div><br><br>

<script language="javascript">
    $("#verClientes").load("view/kmclientes/verclientes.php", {idkm:<?php echo $idkm; ?>});
    //$("#verDespesas").load("view/kmclientes/verdespesas.php", {idkm:<?php echo $idkm; ?>});
</script>