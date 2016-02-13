<?php
if(!isset($_SESSION)) session_start();

$nomelogin = $_SESSION['nomelogin'];
$idlogin = $_SESSION['idlogin'];

include_once('../../dados/config.php');
require_once('../../dados/classes.php');
$consulta = new banco();

$linkurl = explode("/",$_SERVER['SCRIPT_NAME']);
$path = $linkurl[count($linkurl)-2];

$id = $_REQUEST['idkm'];
$tipo = $_REQUEST['tipo'];

$dados = $consulta->PDOSelecionarPadrao("A.*, B.NOME motorista, C.nome usuario",
        "ZPortareCheckList A (NOLOCK)
            LEFT JOIN ZPortareLogin C (NOLOCK) ON A.idusuario = C.id
            JOIN PFUNC B (NOLOCK) ON B.CHAPA = A.idmotorista AND B.CODCOLIGADA = 5", "A.idkm = " . $id . " and A.tipo = '".$tipo."'");
$d = new ArrayIterator($dados);
$valor = $d->current();

?>

<!DOCTYPE html>
<html lang="pt_br">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema Portare</title>
    <link rel="shortcut icon" href="../../theme/default/images/portare.ico" >
    <link rel="icon" type="image/gif" href="../../theme/default/images/portare.ico" >

    <!-- Bootstrap -->
    <link href="../../theme/default/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../theme/default/css/bootstrap-switch.min.css" rel="stylesheet">

    <script src="../../theme/default/js/jquery-1.11.1.min.js"></script>
    <script src="../../theme/default/js/bootstrap.min.js"></script>
    <script src="../../theme/default/js/bootstrap-switch.min.js"></script>

    <style>
        body {
            padding-top: 0px;
        }
    </style>

    <script>
        $(function() {
            $("[type='checkbox']").not("[data-switch-no-init]").bootstrapSwitch();
        });

        function valida(campo){
            var item = $("#" + campo+ ":checked").val();
            var idcheck = $("#idcheck").val();
            //alert ('Conteúdo: ' + item + ' .:. Campo: ' + campo);
            $.post("acao.php", {campo: campo, item: item, idcheck: idcheck});
        }
    </script>

</head>

<body>

<h3>Registro de Check List - <small><?php if ($tipo == 'S') echo 'Saída'; else echo 'Chegada'; ?></small></h3>
<input type="hidden" id="idcheck" value="<?php echo $valor->id; ?>">
<input type="hidden" id="idusuariockeck" value="<?php echo $idlogin; ?>">


<table class="table">
    <tr>
        <td>2 Extintores por carreta. PQS 8KG (Truck) ou 12kg(Carreta) com sele e recarga na validade e fácil acesso e +1 ext. na cabine 2kg</td>
        <td><input id="p1" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p1==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Baldes em aluminío com cabo terra um pequeno e um grande</td>
        <td><input id="p2" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p2==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td> Batoque - 03 unidades (por carreta)</td>
        <td><input id="p3" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p3==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td> Bolsa para veículo - kit de epi´s</td>
        <td><input id="p4" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p4==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Botas de Borracha</td>
        <td><input id="p5" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p5==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Buzina</td>
        <td><input id="p6" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p6==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Cabo Terra</td>
        <td><input id="p7" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p7==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Cabo Terra Quinta Roda</td>
        <td><input id="p8" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p8==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Calços de Madeira</td>
        <td><input id="p9" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p9==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Cada de Chuva</td>
        <td><input id="p10" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p10==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Capachete MAS ou Similar c/ jugular</td>
        <td><input id="p11" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p11==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Chave de Roda</td>
        <td><input id="p12" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p12==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Chave geral bliindada com L/D ligado no polo positivo da bateria</td>
        <td><input id="p13" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p13==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Cinto Trava Quedas</td>
        <td><input id="p14" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p14==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Condição de pressão dos pneus</td>
        <td><input id="p15" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p15==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Cone Grande (4)</td>
        <td><input id="p16" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p16==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Cone Pequeno (12-Bitrem)</td>
        <td><input id="p17" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p17==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Espelhos Retrovisores</td>
        <td><input id="p18" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p18==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Esquicho de água</td>
        <td><input id="p19" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p19==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Faróis ou faroletes dianteiros (luz branca ou amarela)</td>
        <td><input id="p20" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p20==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Faróis, lanternas e setas sem defeito (fixação, sem trica e funcionando)</td>
        <td><input id="p21" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p21==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Freios Estacionamento e Marcha</td>
        <td><input id="p22" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p22==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Fiação contida em dutos (emendas só nas caixas de passagem), isolamento perfeita, bateria isolada</td>
        <td><input id="p23" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p23==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Ficha de Emergência e Envelope</td>
        <td><input id="p24" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p24==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>FISPQ</td>
        <td><input id="p25" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p25==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Fita Zebrada rolo c/ 100m + dispositivo p/ sustenção (2 fista)</td>
        <td><input id="p26" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p26==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Inchada anti-faisca</td>
        <td><input id="p27" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p27==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Lanterna ante-explosão c/ pilhas</td>
        <td><input id="p28" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p28==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Lona Abafadora</td>
        <td><input id="p29" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p29==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Luvas de PVC</td>
        <td><input id="p30" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p30==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Luz Interna</td>
        <td><input id="p31" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p31==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Macaco</td>
        <td><input id="p32" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p32==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Manta absorvente</td>
        <td><input id="p33" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p33==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Martelo de Borracha e Madeira - 1 unidade de cada</td>
        <td><input id="p34" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p34==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Máscara semi-facial</td>
        <td><input id="p35" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p35==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Nível e Pressão do Óleo</td>
        <td><input id="p36" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p36==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Óculos de proteção ampla visão</td>
        <td><input id="p37" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p37==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Pá anti-faísca</td>
        <td><input id="p38" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p38==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Parafusos das rodas checados apertados</td>
        <td><input id="p39" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p39==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Para-brisas</td>
        <td><input id="p40" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p40==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Placas de Simbologia - Perigo afaste-se (2) e Perigo não fume (2)</td>
        <td><input id="p41" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p41==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Pneus em bom estado, com sulcos mínimo de 1,6mm</td>
        <td><input id="p42" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p42==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Step e chave do Step</td>
        <td><input id="p43" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p43==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Tanque Cheio</td>
        <td><input id="p44" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p44==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Tara do Caminhão</td>
        <td><input id="p45" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p45==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Telefone celular chip+cartão</td>
        <td><input id="p46" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p46==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Tirantes de Nylon 10 metros</td>
        <td><input id="p47" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p47==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Triângulos de sinalização</td>
        <td><input id="p48" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p48==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Tanque, válvulas e conexões, isentos de vazamentos, garantindo o bom acondicionamento do produto</td>
        <td><input id="p49" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p49==1) echo 'checked'; ?>></td>
    </tr>
    <tr>
        <td>Vias bloco de requisição (mínimo 6)</td>
        <td><input id="p50" type="checkbox" value="1" onchange="valida(this.id)" <?php if($valor->p50==1) echo 'checked'; ?>></td>
    </tr>
</table>

<div id="msgRetorno" class="msgDiv"> &nbsp; </div>

<div class="text-center">
    <button type="button" class="btn btn-success actions" onclick="window.close()">
        <span class="glyphicon glyphicon-eye-close"></span> Fechar
    </button>
</div>


</body>
</html>