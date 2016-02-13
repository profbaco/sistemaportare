<script src="theme/default/js/functions.js"></script>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4><strong>
                Geração da D.R.E.
            </strong>
        </h4>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td class="text-right"><br>Nível do Portare:</td>
                <td>
                    <select id='vernivel' class="form-control">
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                        <option value='6' selected>6</option>
                    </select>
                </td>
                <td class="text-right"><br>Ordenar por:</td>
                <td>
                    <select id='ordernarpor' class="form-control">
                        <option value='1'>Portare</option>
                        <option value='2'>Corpore RM</option>
                    </select>
                </td>
                <td class="text-right"><br>Imprimir Conta:</td>
                <td>
                    <select id='imprimirconta' class="form-control">
                        <option value='1'>Com Saldo</option>
                        <option value='2' selected>Todas Contas</option>
                    </select>
                </td>
                <td>
                    <a href="javascript:void(0)" class="btn btn-primary" id="btnAtualizar" onclick="gerarDRE()">
                        <span class="glyphicon glyphicon-print"></span> &nbsp;Gerar Relatório
                    </a>
                </td>
            </tr>
        </table>

    </div>
</div>