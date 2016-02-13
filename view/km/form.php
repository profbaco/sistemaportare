<?php
if(!isset($_SESSION)) session_start();

$nomelogin = $_SESSION['nomelogin'];
$idlogin = $_SESSION['idlogin'];

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$acao = $_REQUEST['acao'];
$id = $_REQUEST['id'];
$tipo = $_REQUEST['tipo'];


if ($id>0){
    $dados = $consulta->PDOSelecionarPadrao("A.id, B.nome, A.idmotorista, (A.idmotorista+' - '+C.NOME) motorista, A.valorcombustivel, A.kmsaida,
    A.kmchegada, a.observacao, CONVERT(VARCHAR(15), A.dtorcamento, 103) dtorcamento, convert(varchar(15), A.dtretornoempresa, 103) retorno, convert(varchar(15), A.dtsaidaempresa, 103) dtsaidaempresa,
    case status
       WHEN 0 THEN 'Montagem'
       WHEN 1 THEN 'Aguardando Resposta'
       WHEN 2 THEN 'Aprovado'
       WHEN 3 THEN 'Em Viagem'
       WHEN 4 THEN 'Concluído'
       WHEN 5 THEN 'Cancelado'
    END AS situacao, A.idcaminhao",
        "ZPortareKm A
	JOIN ZPortareLogin B ON A.idusuario = B.id
	JOIN PFUNC C ON A.idmotorista = C.CHAPA ", "C.CODCOLIGADA = 5 AND A.id = " . $id);
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
<input type="hidden" id="idusuario" value="<?php echo $idlogin; ?>">

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <label for="idcaminhao">Caminhão</label>
                <input type="text" id="idcaminhao" class="form-control selectBuscaKm override" placeholder="Localizar Placa do Caminhão">
            </div>
            <div class="col-md-4">
                <label for="valorcombustivel">Valor Combustível</label>
                <input type="text" id="valorcombustivel" class="form-control tipovalor" placeholder="Valor do Combustível" value="<?php echo number_format($valor->valorcombustivel, 2, ',', '.'); ?>">
            </div>

            <div class="col-md-3">
                <label for="kmsaida">Km Saída</label>
                <input type="text" id="kmsaida" class="form-control" placeholder="Km de Saída" value="<?php echo number_format($valor->kmsaida, 0, ',', '.'); ?>" disabled>
            </div>
            <div class="col-md-3">
                <label for="kmchegada">Km Chegada</label>
                <input type="text" id="kmchegada" class="form-control" placeholder="Km de Chegada" value="<?php echo number_format($valor->kmchegada, 0, ',', '.'); ?>" disabled>
            </div>
            <div class="col-md-3">
                <label for="dtsaidaempresa">Dt Saída</label>
                <input type="text" id="dtsaidaempresa" class="form-control datepicker" placeholder="Dt de Saída" value="<?php echo $valor->dtsaidaempresa; ?>">
            </div>
            <div class="col-md-3">
                <label for="dtretornoempresa">Dt Retorno</label>
                <input type="text" id="dtretornoempresa" class="form-control datepicker" placeholder="Dt de Chegada" value="<?php echo $valor->retorno; ?>">
            </div>
        </div>

        <label for="idmotorista">Motorista</label>
        <input type="text" id="idmotorista" class="form-control selectBuscaKm override" placeholder="Localizar por Nome ou CPF">

        <label for="observacao">Observação</label>
        <textarea class="form-control" rows="4" id="observacao" style="width:100%" placeholder="Observação sobre a viagem"><?php echo $valor->observacao; ?></textarea>

        <div class="row">
            <div class="col-md-3">
                <strong>Data: </strong> <?php echo $valor->dtorcamento; ?>
            </div>
            <div class="col-md-5">
                <strong>Usuário: </strong> <?php echo $valor->nome; ?>
            </div>
            <div class="col-md-4">
                <strong>Status: </strong> <?php echo $valor->situacao; ?>
            </div>
        </div>
    </div>
</div>

<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="button" class="btn btn-success actions" onclick="salvarKm()">
        <span class="glyphicon glyphicon-floppy-saved"></span> Salvar
    </button>
</div> &nbsp;
<br> &nbsp;

<script src="theme/default/js/functions.js"></script>
<script src="theme/default/js/select2.min.js"></script>
<script src="theme/default/js/select2_pt-BR.js"></script>

<script language="javascript">
    $(document).ready(function() {
        $(".selectBuscaKm").select2({
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
        echo '$("#idmotorista").select2("data", {id: "'.$valor->idmotorista.'", text: "'.$valor->motorista.'"}); ';
        echo ' $("#idcaminhao").select2("data", {id: "'.$valor->idcaminhao.'", text: "'.$valor->idcaminhao.'"}); ';
    }
    ?>
</script>