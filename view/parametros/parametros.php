<?php
if(!isset($_SESSION)) session_start();

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();


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

<script src="theme/default/js/functions.js"></script>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4><strong>
                Parâmetros do Sistema
            </strong>
        </h4>
    </div>
    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#parametroPortare" data-toggle="tab" aria-expanded="true">Parâmetros Portare</a></li>
            <li class=""><a href="#parametroRM" data-toggle="tab" aria-expanded="false">Parâmetros RM</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="parametroPortare">
                <p>.</p>
            </div>
            <div class="tab-pane fade" id="parametroRM">

                <div class="row">
                    <div class="col-md-6">
                        <label for="codtmv">Tipo de Movimento</label>
                        <input type="text" id="codtmv" class="form-control selectBuscaKm override" placeholder="Selecionar tipo de movimento">

                        <label for="codloc">Local de Estoque</label>
                        <input type="text" id="codloc" class="form-control selectBuscaKm override" placeholder="Selecionar Local de Estoque">

                        <label for="codtb1flx">Conta Financeira</label>
                        <input type="text" id="codtb1flx" class="form-control selectBuscaKm override" placeholder="Selecionar a Conta Financeira">

                        <label for="codtb3flx">Localização</label>
                        <input type="text" id="codtb3flx" class="form-control selectBuscaKm override" placeholder="Selecionar a Localização">

                        <label for="codven1">Motorista</label>
                        <input type="text" id="codven1" class="form-control selectBuscaKm override" placeholder="Selecionar o Motorista">
                    </div>
                    <div class="col-md-6">
                        <label for="codfilial">Filial</label>
                        <input type="text" id="codfilial" class="form-control selectBuscaKm override" placeholder="Selecionar a Filial">

                        <label for="codccusto">Centro de Custo</label>
                        <input type="text" id="codccusto" class="form-control selectBuscaKm override" placeholder="Selecionar o Centro de Custo">

                        <label for="codtb2flx">Forma de Pagamento</label>
                        <input type="text" id="codtb2flx" class="form-control selectBuscaKm override" placeholder="Selecionar a Forma de Pagamento">

                        <label for="codtb4flx">Tipo de Venda</label>
                        <input type="text" id="codtb4flx" class="form-control selectBuscaKm override" placeholder="Selecionar o Tipo de Venda">

                        <label for="idprd">Produto</label>
                        <input type="text" id="idprd" class="form-control selectBuscaKm override" placeholder="Selecionar o Produto/Item da venda">
                    </div>
                </div> <br>

                <a href="#" class="btn btn-info btn-block" onclick="salvarParametros()">Salvar Parâmetros</a>
            </div><br>

            <div id="retornoParametros">&nbsp;</div>
        </div>
    </div>
</div>

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
                        quem: $(this).attr('id'), // aqui esta opulo do gato
                        codfilial: $("#codfilial").val()
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            }
        });
    });


<?php
$sqlparametros = $consulta->PDOSelecionarPadrao("A.CODTMV, B.NOME, A.CODFILIAL, C.NOMEFANTASIA FILIAL, A.CODLOC, D.NOME LOCALESTOQUE,
A.CODCCUSTO, K.NOME CCUSTO, A.CODTB1FLX, E.DESCRICAO TB1, A.CODTB2FLX, F.DESCRICAO TB2,
A.CODTB3FLX, G.DESCRICAO TB3, A.CODTB4FLX, H.DESCRICAO TB4, A.CODVEN1, I.NOME MOTORISTA,
A.IDPRD, (J.CODIGOPRD+' - '+J.NOMEFANTASIA) PRODUTO",
"ZPortareParametros A
	LEFT JOIN TTMV B ON A.CODTMV = B.CODTMV AND B.CODCOLIGADA = 5
	LEFT JOIN GFILIAL C ON A.CODFILIAL = C.CODFILIAL AND C.CODCOLIGADA = 5
	LEFT JOIN TLOC D ON D.CODCOLIGADA = C.CODCOLIGADA AND D.CODFILIAL = C.CODFILIAL AND A.CODLOC = D.CODLOC
	LEFT JOIN FTB1 E ON A.CODTB1FLX = E.CODTB1FLX AND E.CODCOLIGADA = 5
	LEFT JOIN FTB2 F ON A.CODTB2FLX = F.CODTB2FLX AND F.CODCOLIGADA = 5
	LEFT JOIN FTB3 G ON A.CODTB3FLX = G.CODTB3FLX AND G.CODCOLIGADA = 5
	LEFT JOIN FTB4 H ON A.CODTB4FLX = H.CODTB4FLX AND H.CODCOLIGADA = 5
	LEFT JOIN TVEN I ON A.CODVEN1 = I.CODVEN AND I.CODCOLIGADA = 5
	LEFT JOIN TPRD J ON A.IDPRD = J.IDPRD AND J.CODCOLIGADA = 5
	LEFT JOIN GCCUSTO K ON A.CODCCUSTO = K.CODCCUSTO AND K.CODCOLIGADA = 5", "1=1");
$dparametros = new ArrayIterator($sqlparametros);
$valor = $dparametros->current();

if (!empty($valor->CODTMV)) echo '$("#codtmv").select2("data", {id: "'.$valor->CODTMV.'", text: "'.$valor->CODTMV.' - '.$valor->NOME.'"});';
if (!empty($valor->CODFILIAL)) echo '$("#codfilial").select2("data", {id: "'.$valor->CODFILIAL.'", text: "'.$valor->CODFILIAL.' - '.$valor->FILIAL.'"});';
if (!empty($valor->CODLOC)) echo '$("#codloc").select2("data", {id: "'.$valor->CODLOC.'", text: "'.$valor->CODLOC.' - '.$valor->LOCALESTOQUE.'"});';
if (!empty($valor->CODCCUSTO)) echo '$("#codccusto").select2("data", {id: "'.$valor->CODCCUSTO.'", text: "'.$valor->CODCCUSTO.' - '.$valor->NOME.'"});';
if (!empty($valor->CODTB1FLX)) echo '$("#codtb1flx").select2("data", {id: "'.$valor->CODTB1FLX.'", text: "'.$valor->CODTB1FLX.' - '.$valor->TB1.'"});';
if (!empty($valor->CODTB2FLX)) echo '$("#codtb2flx").select2("data", {id: "'.$valor->CODTB2FLX.'", text: "'.$valor->CODTB2FLX.' - '.$valor->TB2.'"});';
if (!empty($valor->CODTB3FLX)) echo '$("#codtb3flx").select2("data", {id: "'.$valor->CODTB3FLX.'", text: "'.$valor->CODTB3FLX.' - '.$valor->TB3.'"});';
if (!empty($valor->CODTB4FLX)) echo '$("#codtb4flx").select2("data", {id: "'.$valor->CODTB4FLX.'", text: "'.$valor->CODTB4FLX.' - '.$valor->TB4.'"});';
if (!empty($valor->CODVEN1)) echo '$("#codven1").select2("data", {id: "'.$valor->CODVEN1.'", text: "'.$valor->CODVEN1.' - '.$valor->MOTORISTA.'"});';
if (!empty($valor->IDPRD)) echo '$("#idprd").select2("data", {id: "'.$valor->IDPRD.'", text: "'.$valor->PRODUTO.'"});';

?>

</script>