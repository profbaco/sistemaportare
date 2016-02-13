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

    $dados = $consulta->PDOSelecionar("A.id, A.idmotorista, (A.idmotorista+' - '+B.NOME) motorista, A.operadora, A.numero", "ZPortareTelefones A, PFUNC B", "A.idmotorista = B.CHAPA AND B.CODCOLIGADA = 5 AND A.id = " . $id, 0, 1, "A.operadora");
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
    <label for="idmotorista">Descrição</label>
    <input type="text" id="idmotorista" class="form-control selectBuscaMotorista override" placeholder="Localizar por Chapa ou Nome">
    <div class="row">
        <div class="col-md-6">
            <label for="operadora">Operadora</label>
            <!--<input type="text" id="operadora" class="form-control" placeholder="Operadora" value="<?php echo $valor->operadora; ?>">-->
            <select class="form-control" id="operadora">
                <option value="TIM" <?php if ($valor->operadora == 'TIM') echo 'selected'; ?>>TIM</option>
                <option value="CLARRO" <?php if ($valor->operadora =='CLARRO') echo 'selected'; ?>>CLARRO</option>
                <option value="VIVO" <?php if ($valor->operadora =='VIVO') echo 'selected'; ?>>VIVO</option>
                <option value="OI" <?php if ($valor->operadora =='OI') echo 'selected'; ?>>OI</option>
                <option value="NEXTEL" <?php if ($valor->operadora =='NEXTEL') echo 'selected'; ?>>NEXTEL</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="numero">Número</label>
            <input type="text" id="numero" class="form-control telefone" placeholder="Número" value="<?php echo $valor->numero; ?>">
        </div>
    </div>
</div>


<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="button" class="btn btn-success actions" onclick="salvarTelefone()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
    </button>
</div> &nbsp;
<br> &nbsp;

<script src="theme/default/js/functions.js"></script>
<script src="theme/default/js/select2.min.js"></script>
<script src="theme/default/js/select2_pt-BR.js"></script>

<script language="javascript">
    $(document).ready(function() {
        $(".selectBuscaMotorista").select2({
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
        echo '$("#idmotorista").select2("data", {id: "'.$valor->idmotorista.'", text: "'.$valor->motorista.'"});';
    }
    ?>
</script>