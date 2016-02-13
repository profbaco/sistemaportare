<?php
if(!isset($_SESSION)) session_start();
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$id = $_REQUEST['id'];
$pagina = $_REQUEST['pagina'];

if ($id>0){
    $dados = $consulta->PDOSelecionar("A.id, (A.codconta+' - '+B.DESCRICAO) conta, A.codconta, A.codigo, A.descricao, A.considerar, A.tipodespesa, A.valorconta",
               "ZPortarePlanocontas A, CCONTA B", "A.codconta = B.CODCONTA AND B.CODCOLIGADA IN (0, 5) AND A.id = " . $id, 0, 1, "A.codigo");
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
<input type="hidden" id="pagina" value="<?php echo $pagina; ?>">


<div class="col-md-12">
    <label for="codigo">Código da Conta</label>
    <input type="text" id="codigo" class="form-control" placeholder="Código da Conta" value="<?php echo $valor->codigo; ?>" autofocus="true">
    <label for="codconta">Conta Contábil (RM Saldus)</label>
    <input type="text" id="codconta" class="form-control selectBuscaConta override" placeholder="Buscar por Código ou Descrição">
    <div class="row">
        <div class="col-md-4">
            <label for="considerar">Considerar Tabela</label>
            <select class="form-control" id="considerar">
                <option value="" <?php if ($valor->considerar=='') echo 'selected';?>>Não Aplicável</option>
                <option value="D" <?php if ($valor->considerar=='D') echo 'selected';?>>Tabela de DRE</option>
                <option value="F" <?php if ($valor->considerar=='F') echo 'selected';?>>Tabela Fixa</option>
                <option value="P" <?php if ($valor->considerar=='P') echo 'selected';?>>Folha de Pagamento</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="tipodespesa">Tipo de Despesa</label>
            <select class="form-control" id="tipodespesa">
                <option value="" <?php if ($valor->tipodespesa=='') echo 'selected';?>>Não Aplicável</option>
                <option value="D" <?php if ($valor->tipodespesa=='D') echo 'selected';?>>Direta</option>
                <option value="I" <?php if ($valor->tipodespesa=='I') echo 'selected';?>>Indireta</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="valorconta">Valor da Conta</label>
            <input type="text" id="valorconta" class="form-control tipovalor" placeholder="Valor" value="<?php echo number_format($valor->valorconta,2,',','.'); ?>">
        </div>
    </div>

    <div class="alert alert-danger" id="alerta" style="display: none">Esta conta já existe no sistema</div>

    <!--<label for="descricao">Descrição</label>
    <input type="text" id="descricao" class="form-control" placeholder="Descrição da Conta" readonly value="<?php #echo $valor->descricao; ?>">-->

</div>


<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="button" class="btn btn-success actions" onclick="salvarPlanoConta()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
    </button>
</div> &nbsp;
<br> &nbsp;

<script src="theme/default/js/functions.js"></script>
<script src="theme/default/js/select2.min.js"></script>
<script src="theme/default/js/select2_pt-BR.js"></script>

<script language="javascript">
    document.getElementById('codigo').focus();

    $(document).ready(function() {

        $(".selectBuscaConta").select2({
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
                results: function (data) {
                    return {results: data};
                }
            }/*,
            formatResult: resultadoBusca,
            formatSelection: resultadoBusca,
            escapeMarkup: function (m) { return m; }*/
        });

        /*function resultadoBusca(state){
            $("#codconta").val(state.CODCONTA);
            $("#descricao").val(state.DESCRICAO);
            return state.text;
        }*/
    });



    <?php
    if ($acao=='editar') {
        echo '$("#codconta").select2("data", {id: "'.$valor->codconta.'", text: "'.$valor->conta.'"});';
    }
    ?>
</script>