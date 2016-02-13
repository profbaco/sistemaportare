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

<ul class="nav nav-tabs" style="margin-bottom: 15px;">
    <li class="active"><a href="#tabDespesaCaminhao" data-toggle="tab">Caminhões/Agregados</a></li>
    <li><a href="#tabDespesaFixa" data-toggle="tab">Fixas</a></li>
    <li><a href="#tabDespesaAdicional" data-toggle="tab">Adicionais</a></li>
    <?php if ($tipo=='realizado') { ?>
    <li><a href="#tabDespesaRealizadas" data-toggle="tab">Realizadas</a></li>
    <?php } ?>
</ul>
<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade active in" id="tabDespesaCaminhao">

    </div>
    <div class="tab-pane fade" id="tabDespesaFixa">

    </div>
    <div class="tab-pane fade" id="tabDespesaAdicional">

    </div>
    <div class="tab-pane fade" id="tabDespesaRealizadas">

    </div>
</div>

<script language="javascript">
    $("#tabDespesaCaminhao").load("view/kmdespesas/conteudo.php", {idkm: <?php echo $idkm; ?>, agregado: '0,1'})
    $("#tabDespesaFixa").load("view/kmdespesas/conteudo.php", {idkm: <?php echo $idkm; ?>, agregado: '2'})
    $("#tabDespesaAdicional").load("view/kmdespesas/conteudo.php", {idkm: <?php echo $idkm; ?>, agregado: '3'})
    <?php if ($tipo=='realizado') { ?>
    $("#tabDespesaRealizadas").load("view/kmdespesas/conteudo.php", {idkm: <?php echo $idkm; ?>, agregado: '5'})
    <?php } ?>
</script>