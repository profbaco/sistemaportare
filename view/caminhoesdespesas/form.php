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
    $dados = $consulta->PDOSelecionar("B.*, A.descricao",
        "ZPortareDespesas A, ZPortareCaminhoesdespesas B",
        "A.id = B.iddespesa AND B.id = " . $id, 0, 1, "B.id");
    $d = new ArrayIterator($dados);
    $valor = $d->current();
}
?>
<link href="theme/default/css/select2.css" rel="stylesheet">

<style>
    .select2-container .select2-choice {
        padding: 0px;
        height: 34px;
        padding: 2px 0px 0px 8px;

    }
    .override {
        padding: 0px;
    }
</style>

<input type="hidden" id="id" value="<?php echo $id; ?>">
<input type="hidden" id="acao" value="<?php echo $acao; ?>">


<div class="col-md-12">
    <label for="iddespesa">Despesa</label>
    <input type="text" id="iddespesa" class="form-control selectBuscaDespesa override" placeholder="Localizar por Nome">

    <div class="row">
        <div class="col-md-6">
            <label for="periododiario">Tipo de Despesa:</label>
            <select id="periododiario" name="periododiario" class="form-control">
                <option value="365" <?php if($valor->periododiario==365) echo "selected"; ?>>Anual</option>
                <option value="180" <?php if($valor->periododiario==180) echo "selected"; ?>>Semestral</option>
                <option value="90" <?php if($valor->periododiario==90) echo "selected"; ?>>Trimestral</option>
                <option value="60" <?php if($valor->periododiario==60) echo "selected"; ?>>Bimestral</option>
                <option value="30" <?php if($valor->periododiario==30) echo "selected"; ?>>Mensal</option>
                <option value="1" <?php if($valor->periododiario==1) echo "selected"; ?>>Diário</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="valor">Valor</label>
            <input type="text" id="valor" class="form-control tipovalor" placeholder="Valor" value="<?php echo number_format($valor->valor, 2, ',','.'); ?>">
        </div>
    </div>
</div>


<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="button" class="btn btn-success actions" onclick="salvarCaminhaoDespesa()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
    </button>
</div> &nbsp;
<br> &nbsp;

<script src="theme/default/js/functions.js"></script>
<script src="theme/default/js/select2.min.js"></script>
<script src="theme/default/js/select2_pt-BR.js"></script>

<script language="javascript">
    $(document).ready(function() {
        $(".selectBuscaDespesa").select2({
            minimumInputLength: 3,
            allowClear: true,
            ajax: {
                url: "view/busca/busca.php",
                dataType: 'json',
                data: function (term, page) {
                    return {
                        term: term,  // este é o parãmetro que estou passando para a busca
                        page_limit: 10,
                        quem: $(this).attr('id') // aqui esta opulo do gato
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        });
    });

    <?php
    if ($acao=='editar') {
        echo '$("#iddespesa").select2("data", {id: "'.$valor->iddespesa.'", text: "'.$valor->descricao.'"});';
    }
    ?>
</script>