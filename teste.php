<html>
<head>
    <title>Criando linhas dinamicamente em tabelas</title>
    <script LANGUAGE="JavaScript">
        totals =0;
        function adiciona(){
            totals++
            tbl = document.getElementById("tabela")

            var novaLinha = tbl.insertRow(-1);
            var novaCelula;

            if(totals%2==0) cl = "#F5E9EC";
            else cl = "#FBF6F7";

            novaCelula = novaLinha.insertCell(0);

            novaCelula.style.backgroundColor = cl

            novaCelula.innerHTML = "<input type='checkbox' name='chkt' />";

            novaCelula = novaLinha.insertCell(1);
            novaCelula.align = "left";
            novaCelula.style.backgroundColor = cl;
            novaCelula.innerHTML = "&nbsp; Linha"+totals+"";

            novaCelula = novaLinha.insertCell(2);
            novaCelula.align = "left";
            novaCelula.style.backgroundColor = cl;
            novaCelula.innerHTML = "&nbsp;ops3";

            novaCelula = novaLinha.insertCell(3);
            novaCelula.align = "left";
            novaCelula.style.backgroundColor =cl;
            novaCelula.innerHTML = "&nbsp;ops4";
        }
    </script>
</head>
<body>
<table id='tabela' border='0' width='100%'>
    <tr style='background-color:#FBF6F7'>
        <td> &nbsp;</td>
        <td>Coluna um</td>
        <td>Coluna dois</td>
        <td>Coluna tres</td>
    </tr>
</table>
<br />
<input type='button' id='incluir' value='incluir' onclick='adiciona()'/>
</body>
</html>