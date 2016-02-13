<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$id = $_REQUEST['id'];
$idkm = $_REQUEST['idkm'];

if ($id>0){
    $dados = $consulta->PDOSelecionarPadrao("CONVERT(VARCHAR(15), A.data, 103) data, A.litragem, A.valor, A.km, A.requisicao,
A.tipo", "ZPortareKmAbastecimentos A", "A.id = " . $id);
    $d = new ArrayIterator($dados);
    $valor = $d->current();
}
?>

<script src="theme/default/js/functions.js"></script>

<input type="hidden" id="idAbastecimento" value="<?php echo $id; ?>">
<input type="hidden" id="idkmAbastecimento" value="<?php echo $idkm; ?>">
<input type="hidden" id="acaoAbastecimento" value="<?php echo $acao; ?>">

<div class="col-md-12">
    <div class="row">
        <div class="col-md-4">
            <label for="data">Data:</label>
            <input type="text" id="data" class="form-control datepicker" placeholder="Data" value="<?php echo $valor->data; ?>">

            <label for="valor">Valor Total:</label>
            <input type="text" id="valor" class="form-control tipovalor" placeholder="Valor do combustível" value="<?php echo number_format($valor->valor,2,',','.'); ?>">
        </div>
        <div class="col-md-4">
            <label for="litragem">Litragem:</label>
            <input type="text" id="litragem" class="form-control tipovalor" placeholder="Litragem abastecida" value="<?php echo number_format($valor->litragem,2,',','.'); ?>">

            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" class="form-control">
                <option value="S" <?php if($valor->tipo=='S') echo "selected"; ?>>Saída do Caminhão</option>
                <option value="A" <?php if($valor->tipo=='A') echo "selected"; ?>>Abastecimentos no trajeto</option>
                <option value="C" <?php if($valor->tipo=='C') echo "selected"; ?>>Chegada do Caminhão</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="km">Km:</label>
            <input type="text" id="km" class="form-control numeros" placeholder="Km do Hodômetro" value="<?php echo number_format($valor->km,0,',','.'); ?>">

            <label for="km">Código da Requisição:</label>
            <input type="text" id="requisicao" class="form-control" placeholder="Cód. do RM" value="<?php echo $valor->requisicao; ?>">
        </div>
    </div>
</div> &nbsp;



<div id="msgRetornoAbastecimento"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="button" class="btn btn-success actions" onclick="salvarKmAbastecimento()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
    </button>
</div> &nbsp;
<br> &nbsp;