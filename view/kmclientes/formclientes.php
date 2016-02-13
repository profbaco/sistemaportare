<?php
if(!isset($_SESSION)) session_start();
include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$id = $_REQUEST['id'];

if ($acao == 'editar'){
    echo 'entrou';
    $sql = $consulta->PDOSelecionarPadrao("A.*, CONVERT(VARCHAR(15),A.datasaida,103) saida, CONVERT(VARCHAR(15),A.datachegada,103) chegada, B.NOME cliente, C.descricao",
        "ZPortareKmClientes A WITH (NOLOCK)
	JOIN FCFO B WITH (NOLOCK) ON A.idcliente = B.CODCFO AND B.CODCOLIGADA IN (0, 5)
	JOIN ZPortareCombustivel C WITH (NOLOCK) ON A.idcombustivel = C.id",
        "A.id = ".$id);
    $d = new ArrayIterator($sql);
    $valor = $d->current();
}
?>
<link href="theme/default/css/select2.css" rel="stylesheet">
<script>
    $("#horasaida").mask('99:99');
</script>
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
<input type="hidden" id="acao" value="<?php echo $acao; ?>">
<input type="hidden" id="id" value="<?php echo $id; ?>">


<div class="col-md-12">
    <label for="idcliente">Cliente</label>
    <input type="text" id="idcliente" class="form-control selectBuscaCliente override" placeholder="Localizar por Nome ou CNPJ">

    <label for="idcombustivel">Produto</label>
    <input type="text" id="idcombustivel" class="form-control selectBuscaCombustivel override" placeholder="Localizar pela descrição">

    <div class="row">
        <div class="col-md-4">
            <label for="kmchao">Km Chão</label>
            <input type="text" id="kmchao" class="form-control tipovalor" placeholder="Km Chão" value="<?php echo number_format($valor->kmchao, 2, ',','.'); ?>">

            <label for="datasaida">Dt Prev Saída</label>
            <input type="text" id="datasaida" class="form-control datepicker" placeholder="Data Saída" value="<?php echo $valor->saida; ?>">

            <label for="origem">Local de Origem</label>
            <input type="text" id="origem" class="form-control" placeholder="Origem do Caminhão" value="<?php echo $valor->origem; ?>">
        </div>
        <div class="col-md-4">
            <label for="kmasfalto">Km Asfalto</label>
            <input type="text" id="kmasfalto" class="form-control tipovalor" placeholder="Km Asfalto" value="<?php echo number_format($valor->kmasfalto, 2, ',','.'); ?>">

            <label for="datachegada">Dt Prev Chegada</label>
            <input type="text" id="datachegada" class="form-control datepicker" placeholder="Dt Entrega" value="<?php echo $valor->chegada; ?>">

            <label for="destino">Local de Destino</label>
            <input type="text" id="destino" class="form-control" placeholder="Destino do Caminhão" value="<?php echo $valor->destino; ?>">
        </div>
        <div class="col-md-4">
            <label for="horasaida">Hora de Saída</label>
            <input type="text" id="horasaida" class="form-control" placeholder="Horário de Saída" value="<?php echo $valor->horasaida; ?>">

            <label for="volume">Volume (Lt)</label>
            <input type="text" id="volume" class="form-control numeros" placeholder="Volume" value="<?php echo number_format($valor->volume, 0, ',','.'); ?>">

            <label for="kmchegada">Km de Chegada</label>
            <input type="text" id="kmchegada" class="form-control numeros" placeholder="Km de Chegada" value="<?php echo number_format($valor->kmchegada, 0, ',','.'); ?>">
        </div>

    </div>
</div>


<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="button" class="btn btn-success actions" onclick="salvarKmCliente()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
    </button>
</div> &nbsp;
<br> &nbsp;

<script src="theme/default/js/functions.js"></script>
<script src="theme/default/js/select2.min.js"></script>
<script src="theme/default/js/select2_pt-BR.js"></script>

<script language="javascript">
    $(document).ready(function() {
        $(".selectBuscaCliente").select2({
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
            },
            formatResult: resultadoBusca,
            formatSelection: resultadoBusca,
            escapeMarkup: function (m) { return m; }
        });

        $(".selectBuscaCombustivel").select2({
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

    function resultadoBusca(state){
        $("#kmasfalto").val(state.ASFALTO);
        $("#kmchao").val(state.CHAO);
        return state.text;
    }

    <?php
    if ($acao=='editar') {
        echo '$("#idcliente").select2("data", {id: "'.$valor->idcliente.'", text: "'.$valor->cliente.'"});';
        echo '$("#idcombustivel").select2("data", {id: "'.$valor->idcombustivel.'", text: "'.$valor->descricao.'"});';
    }
    ?>
</script>