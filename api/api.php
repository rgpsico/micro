<?php

 error_reporting(E_ALL);
ini_set('display_errors', '1');

$con = odbc_connect(
  'DRIVER={SQL Server};Server=DESKTOP-NO0HBFK\SQLEXPRESS;Database=ERP179',
  '', '');

if (isset($_REQUEST['getContrato'])) {
    $assinaturaFolder = 'https://somosfanaticos.fans/br/futebolbrasileiro/Gigante-brasileiro-se-aproxima-da-contratacao-de-Rodinei-mas-torcida-levanta-campanha-contra-a-chegada-do-lateral-do-Fla-20220819-0041.html';

    $id_contrato = $_REQUEST['id_contrato'] = 1;
    $id_cliente = $_REQUEST['id_cliente'] = 1;

    $sqlContratos = "SELECT
     substring(conteudo, 1, 4000) conteudo1
    ,substring(conteudo, 4001, 4000) conteudo2
    ,substring(conteudo, 8001, 4000) conteudo3
    ,substring(conteudo, 11901, 4000) conteudo4
    ,substring(conteudo, 16001, 4000) conteudo5 
    FROM sf_contratos_item where id=$id_contrato";
    $cur = odbc_exec($con, $sqlContratos) or exit(odbc_errormsg());
    $obs = odbc_fetch_array($cur);
    $conteudo = utf8_encode($obs['conteudo1']);
    $conteudo2 = utf8_encode($obs['conteudo2']);
    $conteudo3 = utf8_encode($obs['conteudo3']);
    $conteudo4 = utf8_encode($obs['conteudo4']);
    $conteudo5 = utf8_encode($obs['conteudo5']);
    echo $conteudo;
    echo '<br>';
    echo $conteudo2;
    echo '<br>';
    echo $conteudo3;
    echo '<br>';
    echo "<div class='ultimo_conteudo'><div class='conteudo_4'>";
    echo $conteudo4;
    echo '</div>';
    echo '<br>';
    echo "<div class='conteudo_5'>";
    echo $conteudo5;
    echo '</div></div>';
    echo "<div class='assinaturaCliente'>
             </div>";
    echo "<img class='assinaturaEmpresa' src='$assinaturaFolder '  width='100px' height='80px'><br>
      _________________________ ";
    echo 'php_xcx';
    echo '</div>';

    echo "<div class='cliente_text'>";
   
    echo '</div>';
    exit;
}





if (isset($_REQUEST['getContratoByIdCliente'])) {
    $id_cliente = 1;
    $sqlContratos = "select * FROM sf_fornecedores_despesas WHERE id_fornecedores_despesas = 5";
    $cur = odbc_exec($con, $sqlContratos) or exit(odbc_errormsg());
    while ($obs = odbc_fetch_array($cur)) {
        $row = [];
        $row['razao_social'] = $obs['razao_social'];
     

        $records[] = $row;
    }
    echo json_encode($records);

    exit;
}

function ms_escape_string($data)
{
    if (!isset($data) or empty($data)) {
        return '';
    }
    if (is_numeric($data)) {
        return $data;
    }

    $non_displayables = [
        '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
        '/%1[0-9a-f]/',             // url encoded 16-31
        '/[\x00-\x08]/',            // 00-08
        '/\x0b/',                   // 11
        '/\x0c/',                   // 12
        '/[\x0e-\x1f]/',             // 14-31
    ];
    foreach ($non_displayables as $regex) {
        $data = preg_replace($regex, '', $data);
    }
    $data = str_replace("'", "''", $data);

    return $data;
}
