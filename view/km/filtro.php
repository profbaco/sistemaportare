<?php
if(!isset($_SESSION)) session_start();

$nomelogin = $_SESSION['nomelogin'];
$idlogin = $_SESSION['idlogin'];

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

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

<form name="form" id="form">
    <input type="hidden" id="acaoFiltro" name="acaoFiltro" value="filtrar">

<div class="row">
    <div class="col-md-3">
        <label for="A.id">Id</label>
        <input type="text" name="A.id" class="form-control" placeholder="#">

        <label for="A.dtorcamentoInicial">Dt Criação Inicial</label>
        <input type="text" name="A.dtorcamentoInicial" class="form-control datepicker" placeholder="Criação Inicial">

        <label for="A.dtsaidaempresaInicial">Dt Saída Inicial</label>
        <input type="text" name="A.dtsaidaempresaInicial" class="form-control datepicker" placeholder="Saída Inicial">
    </div>

    <div class="col-md-3">
        <label for="A.idmov">Cód. RM</label>
        <input type="text" name="A.idmov" class="form-control" placeholder="Id. Movimento">

        <label for="A.dtorcamentoFinal">Dt Criação Inicial</label>
        <input type="text" name="A.dtorcamentoFinal" class="form-control datepicker" placeholder="Criação Final">

        <label for="A.dtsaidaempresaFinal">Dt Saída Inicial</label>
        <input type="text" name="A.dtsaidaempresaFinal" class="form-control datepicker" placeholder="Saída Final">
    </div>

    <div class="col-md-6">
        <label for="A.idcaminhao">Caminhão</label>
        <input type="text" name="A.idcaminhao" id="idcaminhao" class="form-control selectBuscaKm override" placeholder="Localizar Placa do Caminhão">

        <label for="status">Status</label>
        <select name="status" class="form-control">
            <option value="" selected></option>
            <option value="0">Montagem</option>
            <option value="1">Aguardando Resposta</option>
            <option value="2">Aprovado</option>
            <option value="3">Em Viagem</option>
            <option value="4">Concluído</option>
            <option value="5">Cancelado</option>
            <option value="6">Em Carregamento</option>
        </select>

        <label for="A.idusuario">Usuário</label>
        <select name="A.idusuario" class="form-control">
            <option value="" selected></option>
            <?php
            $user = $consulta->PDOSelecionarPadrao("id, nome", "ZPortareLogin", "ativo = 1 order by nome");
            $duser = new ArrayIterator($user);
            while ($duser->valid()){
                $valoruser = $duser->current();
                echo '<option value="'.$valoruser->id.'">'.$valoruser->nome.'</option>';
                $duser->next();
            }
            ?>
        </select>
    </div>
</div>

<label for="A.idmotorista">Motorista</label>
<input type="text" name="A.idmotorista" id="idmotorista" class="form-control selectBuscaKm override" placeholder="Localizar por Nome ou CPF">


<div id="msgRetorno" class="msgDiv"> &nbsp; </div>
<div class="pull-right">
    <button type="button" class="btn btn-danger actions" data-dismiss="modal">
        <span class="glyphicon glyphicon-floppy-remove"></span> Cancelar
    </button>
    <button type="submit" class="btn btn-success actions" id="envia">
        <span class="glyphicon glyphicon-filter"></span> Filtrar
    </button>
</div> &nbsp;
<br> &nbsp;

</form>

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

        jQuery("#form").submit(function(){
            return false;
        });

        jQuery("#envia").click(function(){
            envia_form();
        });

        function envia_form() {
            var msg = '<div class="panel panel-default"><div class="panel-body msgDiv"> Aguarde, carregando conteúdo! </div></div>';
            $("#conteudoPagina").html(msg);
            var formdata = $('#form').serialize();
            //var formdata = $('#id :input').serialize();

            jQuery.ajax({
                type: "POST",
                url: "view/km/list.php",
                dataType: "html",
                data: formdata,
                success: function(response){
                    $("#modalFiltro").modal('hide');
                    $("#conteudoPagina").html(response);
                },
                error: function(){
                    alert("Ocorreu um erro durante a requisição");
                }
            });
        }
    });
</script>