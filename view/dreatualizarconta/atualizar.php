<script src="theme/default/js/functions.js"></script>

<script language="javascript">
    function atualizaSaldo(){
        //var nivel = $("#vernivel").val();
        var periodoInicial = $("#periodoInicial").val();
        var periodoFinal = $("#periodoFinal").val();
        //var ordernarpor = $("#ordernarpor").val();
        var acao = 'atualizarsaldo'
        if (periodoInicial==''){
            $("#vercontas").html('O período inicial não pode ficar em branco');
            return false;
        }
        if (periodoFinal==''){
            $("#vercontas").html('O período final não pode ficar em branco');
            return false;
        }
        $("#vercontas").html('<center><img src="theme/default/images/load.gif"> Aguarde, atualizando saldo das contas!<br>Este processo poderá demorar alguns minutos.</center>');
        $("#vercontas").load('view/dreatualizarconta/processar.php', {acao:acao, periodoFinal: periodoFinal, periodoInicial:periodoInicial});
    }
</script>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4><strong>
                Atualização de Contas da DRE
            </strong>
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2">
                <label for="periodoInicial">Período Inicial</label>
                <input type="text" id="periodoInicial" class="form-control datepicker" placeholder="Data Inicial">
            </div>
            <div class="col-md-2">
                <label for="periodoFinal">Período Final</label>
                <input type='text' id='periodoFinal' class="form-control datepicker" placeholder="Data Final">
            </div>
            <div class="col-md-8"><br>
                <a href="javascript:void(0)" class="btn btn-primary" id="btnAtualizar" onclick="atualizaSaldo()">
                    <span class="glyphicon glyphicon-print"></span> &nbsp;Atualizar Saldo
                </a>
            </div>
        </div>
        <div id="vercontas" class="text-center text-danger">&nbsp;</div>
    </div>
    <div class="panel-footer">
        <?php
        require("../../dados/config.php");
        require("../../dados/classes.php");
        $consulta = new banco();

        $sql = $consulta->PDOSelecionarPadrao("CONVERT(VARCHAR(15), A.datainicial, 103) dtinicial, CONVERT(VARCHAR(15), A.datafinal, 103) dtfinal,
B.nome, CONVERT(VARCHAR(15), A.datacriacao, 103) dtcriado, convert(VARCHAR(15), A.datacriacao, 108) hrcriado", "ZPortareDataBase A
	JOIN ZPortareLogin B ON A.idusuario = B.id", "1=1");
        $d = new ArrayIterator($sql);
        $valor = $d->current();
        echo '<strong>Último período atualizado: </strong> '.$valor->dtinicial.' - '.$valor->dtfinal;
        echo '<br><strong>Data/Hora atualização: </strong> '.$valor->dtcriado.' ás '.$valor->hrcriado;
        echo '<br><strong>Atualizado por: </strong> '.$valor->nome;
        ?>
    </div>
</div>