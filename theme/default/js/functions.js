$(function() {
	$('[rel=tooltip]').tooltip();

    $('[rel=popover]').popover({
        html: 'true',
        trigger: 'hover',
        title: '<b>Resumo da Viagem</b>',
        placemen: 'top'
    });

    $(".datepicker").mask('99/99/9999');
    $('.datepicker').datepicker({autoclose: "true", format: "dd/mm/yyyy"});

    $(".cep").mask('99999-999');
    $(".horas").mask('99:99');
    $(".tipovalor").maskMoney({thousands: '.', decimal: ','});
    $(".valorkm").maskMoney({thousands: '.', decimal: ',', precision: 3});
    $(".numeros").maskMoney({thousands: '.', decimal: ',', precision: 0});
    var masks = ['(00) 00000-0000', '(00) 0000-00009'],
        maskBehavior = function(val, e, field, options) {
            return val.length > 14 ? masks[0] : masks[1];
        };
    $('.telefone').mask(maskBehavior, {onKeyPress:
        function(val, e, field, options) {
            field.mask(maskBehavior(val, e, field, options), options);
        }
    });

    $('#myTab a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    });

});

function carregaPagina(url){
    var msg = '<div class="panel panel-default"><div class="panel-body msgDiv"><img src="theme/default/images/load.gif"> Aguarde, carregando conteúdo! </div></div>';
    $("#conteudoPagina").html(msg);
    $("#conteudoPagina").load(url);
}

function carregaLinha(url, idLinha){
    $.get( url, {idlinha: idLinha}, function( data ) {
        $("#linha"+idLinha).replaceWith(data);
    });
}


<!--============================== Máscara do CNPJ/CPF ==============================-->
function mascaraMutuario(o,f){
    v_obj=o
    v_fun=f
    setTimeout('execmascara()',1)
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function cpfCnpj(v){
    //Remove tudo o que não é dígito
    v=v.replace(/\D/g,"")
    if (v.length < 14) { //CPF
        //Coloca um ponto entre o terceiro e o quarto dígitos
        v=v.replace(/(\d{3})(\d)/,"$1.$2")
        //Coloca um ponto entre o terceiro e o quarto dígitos
        //de novo (para o segundo bloco de números)
        v=v.replace(/(\d{3})(\d)/,"$1.$2")
        //Coloca um hífen entre o terceiro e o quarto dígitos
        v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
    } else { //CNPJ
        //Coloca ponto entre o segundo e o terceiro dígitos
        v=v.replace(/^(\d{2})(\d)/,"$1.$2")
        //Coloca ponto entre o quinto e o sexto dígitos
        v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3")
        //Coloca uma barra entre o oitavo e o nono dígitos
        v=v.replace(/\.(\d{3})(\d)/,".$1/$2")
        //Coloca um hífen depois do bloco de quatro dígitos
        v=v.replace(/(\d{4})(\d)/,"$1-$2")
    }
    return v
}

<!--============================== Função auto-complete de CEP ==============================-->
function getEndereco() {
    // Se o campo CEP não estiver vazio
    if($.trim($("#cep").val()) != ""){
        /*Para conectar no serviço e executar o json, precisamos usar a função
         getScript do jQuery, o getScript e o dataType:"jsonp" conseguem fazer o cross-domain, os outros
         dataTypes não possibilitam esta interação entre domínios diferentes
         Estou chamando a url do serviço passando o parâmetro "formato=javascript" e o CEP digitado no formulário
         http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep").val() */
        $.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep").val(), function(){
            // o getScript dá um eval no script, então é só ler!
            //Se o resultado for igual a 1
            //if(resultadoCEP["resultado"]){
            if(resultadoCEP["resultado"]==1){
                // troca o valor dos elementos
                $("#endereco").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));
                $("#bairro").val(unescape(resultadoCEP["bairro"]));
                $("#cidade").val(unescape(resultadoCEP["cidade"]));
                $("#uf").val(unescape(resultadoCEP["uf"]));
            }else{
                alert("Não foi encontrado nenhum endereço para este CEP");
            }
        });
    }
}

<!--============================== Funções das modais ==============================-->
function abrirModal(acao, id, url){
    $("#modalConteudo").html('<img src="theme/default/images/load.gif"> Aguarde, processando informação!');
    if(id==0){
        if (acao=="inserir"){
            $("#modalTitulo").html('Novo Registro');
        } else {
            $("#modalTitulo").html('Filtrar Registro');
        }
    } else {
        if (acao=="editar"){
            $("#modalTitulo").html('Editando Registro');
        } else if(acao=="despesas") {
            $("#modalTitulo").html('Despesas Vinculadas');
        } else if(acao=="clientes") {
            $("#modalTitulo").html('Vinculos de Clientes');
        } else if(acao=="montagem") {
            $("#modalTitulo").html('Montagem de D.R.E.');
        } else if(acao=="status") {
            $("#modalTitulo").html('Alteração de Status');
        }
    }
    if((url=='view/movimentacao/forminsert.php?tipo=c') || (url=='view/movimentacao/forminsert.php?tipo=f') || (url=='view/movimentacao/formedit.php?tipo=f') || (url=='view/movimentacao/formedit.php?tipo=c')) {
        $("#modalForm").addClass("bigModal");
    } else if(url=='view/kmclientes/list.php') {
        $("#modalForm").addClass("veryBigModal");
    } else {
        $("#modalForm").removeClass("veryBigModal");
        $("#modalForm").removeClass("bigModal");
    }
    $("#modalConteudo").load(url, {acao: acao, id: id});
    $("#modalCadastro").modal('show');
}

function abrirModalExtra(acao, id, url){
    $("#modalConteudoExtra").html('<img src="theme/default/images/load.gif"> Aguarde, processando informação!');
    if(id==0){
        $("#modalTituloExtra").html('Novo Registro');
    } else {
        $("#modalTituloExtra").html('Editando Registro');
    }
    $("#modalConteudoExtra").load(url, {acao: acao, id: id});
    $("#modalExtra").modal('show');
}

<!-- Opções para Excluir -->
function modalExcluir(url, id){
    $('#idExcluir').val(id);
    $('#urlExcluir').val(url);
    $('#msgExcluir').html('');
    $("#modalExcluir").modal('show');
}
function confirmaExcluir(){
    var acao = 'excluir';
    var id = $("#idExcluir").val();
    var url = $("#urlExcluir").val();
    $('#msgExcluir').html('<img src="theme/default/images/load.gif"> Aguarde, validando registro para excluir!');
    $.post(url, {acao: acao, id: id},
        function (retorno){
            $('#msgExcluir').html(retorno)
        }
    )
}

<!-- Opções de Filtros -->
function modalFiltro(url){
    /*if(url=='lancamentofiltro.php') {
        $("#modalFiltro").addClass("bigModal");
    } else {
        $("#modalFiltro").removeClass("bigModal");
    }*/
    var msg = "<div class='widget-container fluid-height clearfix text-center msgTexto'><br><img src='theme/default/images/load.gif'> Aguarde, processando informação!<br><br></div>";
    $("#mostrarConteudoFiltro").html(msg);
    $("#mostrarConteudoFiltro").load(url);
    $("#modalFiltro").modal('show');
}


<!--============================== Parte do Caminhões/Bitrem/Carreta sobre a parte de Despesas ==============================-->
function CarregarDespesas(placa){
    $("#CarregaDespesas").load('view/caminhoesdespesas/list.php', {placa: placa});
}
function CarregarDespesasCliente(idkm, idcliente){
    $("#verDespesas").load('view/kmclientes/verdespesas.php', {idkm: idkm, idcliente:idcliente});
}
function CarregarAgregados(placa, tipo){
    $("#modalConteudo").html('<img src="theme/default/images/load.gif"> Aguarde, processando informação!');
    $("#modalTitulo").html('Visualização de Agregados');
    $("#modalForm").removeClass("bigModal");
    $("#modalConteudo").load('view/'+tipo+'/agregados.php', {placa: placa, tipo:tipo});
    $("#modalCadastro").modal('show');
}
function FecharDespesas(){
    $("#CarregaDespesas").html('');
}
function PaginacaoDespesas(placa, pagina){
    $("#CarregaDespesas").load('view/caminhoesdespesas/list.php?pagina='+pagina, {placa: placa});
}


<!--============================== Ações dos Botões ==============================-->
function logarSistema(){
    var email = $("#inputEmail").val();
    var senha = $("#inputSenha").val();

    if (email==''){
        var msg = '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>O E-mail não pode ficar em branco</div> &nbsp;';
        $("#msgRetorno").html(msg);
        return false;
    }
    if (senha==''){
        var msg = '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>A Senha não pode ficar em branco</div> &nbsp;';
        $("#msgRetorno").html(msg);
        return false;
    }

    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, validando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('valida.php', {email:email, senha:senha},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}


function salvarDespesa(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var descricao = $("#descricao").val();
    var valor = $("#valor").val();
    var tipo = $("#tipo").val();

    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/despesas/actions.php', {id:id, acao:acao, descricao:descricao, valor:valor, tipo:tipo},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarCombustivel(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var descricao = $("#descricao").val();

    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/combustivel/actions.php', {id:id, acao:acao, descricao:descricao},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarTelefone(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var idmotorista = $("#idmotorista").val();
    var operadora = $("#operadora").val();
    var numero = $("#numero").val();

    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/telefones/actions.php', {id:id, acao:acao, idmotorista:idmotorista, operadora:operadora, numero:numero},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarUsuario(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var nome = $("#nome").val();
    var email = $("#email").val();
    var ativo = $("#ativo").val();
    var senha = $("#senha").val();
    var idperfil = $("#idperfil").val();

    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/usuarios/actions.php', {id:id, acao:acao, nome:nome, email:email, ativo:ativo, senha:senha, idperfil:idperfil},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarPerfil(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var nome = $("#nome").val();

    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/perfil/actions.php', {id:id, acao:acao, nome:nome},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarTelefone(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var nome = $("#nome").val();
    var email = $("#email").val();
    var ativo = $("#ativo").val();
    var senha = $("#senha").val();
    var idperfil = $("#idperfil").val();
    if (nome==''){
        var msg = '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>O Nome não pode ficar em branco</div>';
        $("#msgRetorno").html(msg);
        return false;
    }
    if (email==''){
        var msg = '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>O E-mail não pode ficar em branco</div>';
        $("#msgRetorno").html(msg);
        return false;
    }
    if ((senha=='') && (acao=='inserir')){
        var msg = '<div class="alert alert-dismissable alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>A Senha não pode ficar em branco</div>';
        $("#msgRetorno").html(msg);
        return false;
    }

    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/usuarios/actions.php', {id:id, acao:acao, nome:nome, email:email, ativo:ativo, senha:senha, idperfil:idperfil},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarPlanoConta(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var codigo = $("#codigo").val();
    var codconta = $("#codconta").val();
    var considerar = $("#considerar").val();
    var tipodespesa = $("#tipodespesa").val();
    var pagina = $("#pagina").val();
    var valorconta = $("#valorconta").val();

    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/planocontas/actions.php', {id:id, acao:acao, codigo:codigo, codconta:codconta, considerar:considerar, tipodespesa:tipodespesa, pagina:pagina, valorconta:valorconta},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarKm(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var idusuario = $("#idusuario").val();
    var idcaminhao = $("#idcaminhao").val();
    var idmotorista = $("#idmotorista").val();
    var observacao = $("#observacao").val();
    var valorcombustivel = $("#valorcombustivel").val();
    var kmsaida = $("#kmsaida").val();
    var kmchegada = $("#kmchegada").val();
    var dtretornoempresa = $("#dtretornoempresa").val();
    var dtsaidaempresa = $("#dtsaidaempresa").val();
    var status = 0;

    if (idcaminhao==''){
        var msg = "<div class='text-center msgDiv bg-danger'> O Caminhão não pode ficar em branco! </div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    if (idmotorista==''){
        var msg = "<div class='text-center msgDiv bg-danger'>O Motorista não pode ficar em branco!</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    if (valorcombustivel==''){
        var msg = "<div class='text-center msgDiv bg-danger'>O valor do combustível não pode ficar em branco!</div>";
        $("#msgRetorno").html(msg);
        return false;
    }


    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/km/actions.php', {id:id, acao:acao, idusuario:idusuario, idcaminhao:idcaminhao, status:status, kmsaida:kmsaida, dtsaidaempresa:dtsaidaempresa,
                idmotorista:idmotorista, observacao:observacao, valorcombustivel:valorcombustivel, kmchegada: kmchegada, dtretornoempresa:dtretornoempresa},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarCaminhaoDespesa(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var iddespesa = $("#iddespesa").val();
    var periododiario = $("#periododiario").val();
    var valor = $("#valor").val();
    var placa = $("#placa").val();
    if (iddespesa==''){
        var msg = "<div class='text-center msgDiv'>A despesa não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/caminhoesdespesas/actions.php', {id:id, acao:acao, iddespesa:iddespesa, periododiario:periododiario, valor:valor, placa:placa},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarKmDespesa(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var idkm = $("#idkm").val();
    var iddespesa = $("#iddespesa").val();
    var periododiario = $("#periododiario").val();
    var valor = $("#valor").val();
    var agregado = $("#agregado").val();

    if (iddespesa==''){
        var msg = "<div class='text-center msgDiv'>A Despesa não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    if (valor==''){
        var msg = "<div class='text-center msgDiv'>O Valor não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/kmdespesas/actions.php', {id:id, acao:acao, idkm:idkm, iddespesa:iddespesa, periododiario:periododiario, valor:valor, agregado:agregado},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarKmCliente(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var idkm = $("#idkm").val();
    var idcliente = $("#idcliente").val();
    var idcombustivel = $("#idcombustivel").val();
    var kmchao = $("#kmchao").val();
    var kmasfalto = $("#kmasfalto").val();
    var datasaida = $("#datasaida").val();
    var datachegada = $("#datachegada").val();
    var horasaida = $("#horasaida").val();
    var volume = $("#volume").val();
    var origem = $("#origem").val();
    var destino = $("#destino").val();
    var kmchegada = $("#kmchegada").val();

    if (idcliente==''){
        var msg = "<div class='text-center msgDiv'>O Cliente não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    if (idcombustivel==''){
        var msg = "<div class='text-center msgDiv'>O Combustível não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/kmclientes/actions.php', {id:id, acao:acao, idkm:idkm, idcliente:idcliente, idcombustivel:idcombustivel, kmchao:kmchao, kmchegada:kmchegada,
            kmasfalto:kmasfalto, datasaida:datasaida, datachegada:datachegada, horasaida:horasaida, volume:volume, origem: origem, destino:destino},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarKmClienteDespesa(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var idkm = $("#idkm").val();
    var idcliente = $("#idcliente").val();
    var iddespesa = $("#iddespesa").val();
    var periododiario = $("#periododiario").val();
    var valor = $("#valor").val();

    if (iddespesa==''){
        var msg = "<div class='text-center msgDiv'>A Despesa não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    if (valor==''){
        var msg = "<div class='text-center msgDiv'>O Valor não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/kmclientes/actions.php', {id:id, acao:acao, idkm:idkm, iddespesa:iddespesa, periododiario:periododiario, valor:valor, idcliente:idcliente},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarKmAbastecimento(){
    var id = $("#idAbastecimento").val();
    var acao = $("#acaoAbastecimento").val();
    var idkm = $("#idkmAbastecimento").val();
    var data = $("#data").val();
    var litragem = $("#litragem").val();
    var valor = $("#valor").val();
    var tipo = $("#tipo").val();
    var km = $("#km").val();
    var requisicao = $("#requisicao").val();

    if (data==''){
        var msg = "<div class='text-center msgDiv'>A Data não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    if ((litragem=='') && (tipo!='S')){
        var msg = "<div class='text-center msgDiv'>A Litragem não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    if (km==''){
        var msg = "<div class='text-center msgDiv'>O Km não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetornoAbastecimento").html(msg);
    $.post('view/kmabastecimentos/actions.php', {id:id, acao:acao, idkm:idkm, data:data, litragem:litragem, valor:valor, tipo:tipo, km:km, requisicao:requisicao},
        function(retorno){
            $("#msgRetornoAbastecimento").html(retorno);
        }
    )
}

function calcularKm(){
    var idkm = $("#idkm").val();
    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, calculando KM!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/km/calcularkm.php', {idkm:idkm},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarGrupoDre(){
    var id = $("#id").val();
    var acao = $("#acao").val();
    var codigo = $("#codigo").val();
    var descricao = $("#descricao").val();
    if (codigo==''){
        var msg = "<div class='text-center msgDiv'>O código não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    if (descricao==''){
        var msg = "<div class='text-center msgDiv'>A Descrição não pode ficar em branco</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/dregrupo/actions.php', {id:id, acao:acao, codigo:codigo, descricao:descricao},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function salvarDreMontagem(){
    var idgrupo = $("#idgrupo").val();
    var acao = $("#acaoMontagem").val();
    var idconta = $("#idconta").val();
    if (idconta==''){
        var msg = "<div class='text-center msgDiv'>A Conta Contábil não pode ficar em branco!</div>";
        $("#msgRetorno").html(msg);
        return false;
    }
    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/dremontagem/actions.php', {idgrupo:idgrupo, acao:acao, idconta:idconta},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

function alterarStatus(){
    var id = $("#id").val();
    var status = $("#status").val();

    var msg = "<div class='text-center msgDiv'><img src='theme/default/images/load.gif'> Aguarde, salvando registro!</div>";
    $("#msgRetorno").html(msg);
    $.post('view/kmstatus/actions.php', {id:id, status:status},
        function(retorno){
            $("#msgRetorno").html(retorno);
        }
    )
}

/*============================= SCRIPTS PARA A DRE =============================*/
function gerarDRE(){
    var vernivel = $("#vernivel").val();
    var ordenarpor = $("#ordernarpor").val();
    var imprimirconta = $("#imprimirconta").val();
    window.open('view/relatorios/gerardreresultado.php?vernivel='+vernivel+'&ordenarpor='+ordenarpor+'&imprimirconta='+imprimirconta);
}

function verSomenteSaldo(){
    var movimentacao = $("#movimentacao").val();
    carregaPagina('view/relatorios/dreglobal.php?movimentacao='+movimentacao);
}

function imprimirDREMotorista(){
    var chapa = $("#idmotorista").val();
    window.open('view/relatorios/pontodeequilibriopormotoristaresultado.php?chapa='+chapa);
    /*carregaPagina('view/relatorios/pontodeequilibriopormotoristaresultado.php?chapa='+chapa);*/
}

function imprimirDREPlaca(){
    var placa = $("#codccusto").val();
    var resultado = $("#resultado:checked").val();
    /*window.open('view/relatorios/pontodeequilibrioporplacaresultado.php?placa='+placa);*/
    window.open('view/relatorios/'+resultado+'?placa='+placa);
}



/*============================= Salvar Parâmetros =============================*/
function salvarParametros(){
    var codtmv = $("#codtmv").val();
    var codloc = $("#codloc").val();
    var codtb1flx = $("#codtb1flx").val();
    var codtb3flx = $("#codtb3flx").val();
    var codven1 = $("#codven1").val();
    var codfilial = $("#codfilial").val();
    var codccusto = $("#codccusto").val();
    var codtb2flx = $("#codtb2flx").val();
    var codtb4flx = $("#codtb4flx").val();
    var idprd = $("#idprd").val();
    $.post('view/parametros/actions.php', {codtmv:codtmv, codloc:codloc, codtb1flx:codtb1flx, codtb2flx:codtb2flx,
        codtb3flx:codtb3flx, codtb4flx:codtb4flx, codfilial: codfilial, codven1:codven1, codccusto:codccusto,
        idprd:idprd}, function(retorno){
            $("#retornoParametros").html(retorno);
    })
}