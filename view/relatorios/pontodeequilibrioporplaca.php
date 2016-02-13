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
<script src="theme/default/js/select2.min.js"></script>
<script src="theme/default/js/select2_pt-BR.js"></script>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4><strong>
                Relatório de Ponto de Equilíbrio - Por Placa
            </strong>
        </h4>
    </div>
    <div class="panel-body">

        <input type="text" id="codccusto" class="form-control selectBuscaConta override" placeholder="Localizar Centro de Custo (Placa) por Código ou Descrição">

        <br><br>
        <div class="radio">
            <label><input type="radio" name="resultado" id="resultado" value="pontodeequilibrioporplacaresultado.php" checked>Sintético</label>
            &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            <label><input type="radio" name="resultado" id="resultado" value="pontodeequilibrioporplacaresultadoanalitico.php">Analítico</label>
        </div>
        <br><br>

        <a href="javascript:void(0)" class="btn btn-primary btn-block" onclick="imprimirDREPlaca()">
            <span class="glyphicon glyphicon-print"></span> Imprimir
        </a>

    </div>
</div>

<script language="javascript">
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
                results: function (data, page) {
                    return {results: data};
                }
            }
        });
    });
</script>