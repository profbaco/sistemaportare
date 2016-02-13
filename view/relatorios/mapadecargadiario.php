<!DOCTYPE html>
<html lang="pt_br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Portare - Mapa de Carga Diário</title>

    <!-- Bootstrap -->
    <link href="../../theme/default/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../theme/default/css/style.css" rel="stylesheet">
    <link href="../../theme/default/css/datepicker.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="../../theme/default/js/jquery-1.11.1.min.js"></script>
    <script src="../../theme/default/js/bootstrap.min.js"></script>
    <script src="../../theme/default/js/bootstrap-datepicker.js"></script>
    <script src="../../theme/default/js/jquery.mask.min.js"></script>
    <script src="../../theme/default/js/jquery.maskMoney.js"></script>
    <script src="../../theme/default/js/functions.js"></script>

    <style>
        html,body{
            margin: 0px 5px 0px 0px;
            padding: 0px 5px 0px 0px;
            top: 0px;
        }
    </style>

    <script>
        function processaBusca(){
            $("#resultado").html('<center><img src="../../theme/default/images/load.gif"> Aguarde, buscando registros.</center>');
            var dtinicial = $("#dtinicial").val();
            var dtfinal = $("#dtfinal").val();
            $("#resultado").load('mapadecargadiarioresultado.php', {dtinicial: dtinicial, dtfinal: dtfinal});
        }
    </script>

</head>
<body>

<div class="row">
    <div class="col-md-4">
        <label for="dtinicial"><strong>Data Inicial</strong></label>
        <input type="text" class="form-control datepicker" id="dtinicial">
    </div>
    <div class="col-md-4">
        <label for="dtfinal"><strong>Data Final</strong></label>
        <input type="text" class="form-control datepicker" id="dtfinal">
    </div>
    <div class="col-md-4">
        <label for="dtinicial">&nbsp;</label>
        <button class="btn btn-block btn-success" onclick="processaBusca()">
            <i class="glyphicon glyphicon-search"></i> Localizar
        </button>
    </div>
</div>

<div id="resultado"></div>
</body>
</html>