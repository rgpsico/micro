<?php

$con = odbc_connect(
    'DRIVER={SQL Server};Server=DESKTOP-NO0HBFK\SQLEXPRESS;Database=ERP003',
    '', '');
 error_reporting(E_ALL);
ini_set('display_errors', '1');

if (isset($_POST['btnDelFile'])) {
    $uploadpath = 'Pessoas/';

    if (file_exists($uploadpath.'/'.$arquivoName)) {
        unlink($uploadpath.'/'.$arquivoName);
        echo 'YES';
    }
}

if (isset($_POST['image'])) {
    $contrato_conteudo = $_REQUEST['contato_html'];
    $contrato = $_COOKIE['cookie_contrato'] = 0;
    $cliente_id = 2;

    $allowedExts = ['gif', 'jpeg', 'jpg', 'png', 'pdf', 'GIF', 'JPEG', 'JPG', 'PNG', 'PDF'];

    $base64string = $_REQUEST['image'];
    $uploadpath = 'Pessoas/';
    $extension = explode('.', @$_FILES['file']['name']);
    $extension = $extension[count($extension) - 1];

    if (!is_dir($uploadpath)) {
        mkdir($uploadpath, 0777, true);
    }

    $parts = explode(';base64,', $base64string);
    $imageparts = explode('image/', @$parts[0]);
    $imagetype = $imageparts[1];
    $imagebase64 = base64_decode($parts[1]);
    $file = $uploadpath.uniqid().'.png';
    file_put_contents($file, $imagebase64);
}

if (isset($_POST['uploadPDF'])) {
    $contrato = $_COOKIE['cookie_contrato'];
    $id_cliente = $_POST['id_cliente'];
    $nome_arquivo = $contrato.'-'.$id_cliente.'assinatura'.'.pdf';

    $allowedExts = ['gif', 'jpeg', 'jpg', 'png', 'pdf', 'GIF', 'JPEG', 'JPG', 'PNG', 'PDF'];

    $base64string = $_FILES['pdf']['tmp_name'];

    $uploadpath = 'Pessoas/';
    $extension = explode('.', @$_FILES['pdf']['name']);
    $extension = $extension[count($extension) - 1];

    if (!is_dir($uploadpath)) {
        mkdir($uploadpath, 0777, true);
    }

    $parts = explode(';base64,', $base64string);
    $imageparts = explode('image/', @$parts[0]);
    $imagetype = $imageparts[0];
    $imagebase64 = base64_decode($parts[0]);

    move_uploaded_file(
        $_FILES['pdf']['tmp_name'],
        $uploadpath.$nome_arquivo
    );

    $contrato_conteudo = $_REQUEST['contato_html'];

    insertContratoFornecedor($con, $id_cliente, $contrato, $nome_arquivo, $contrato_conteudo);
    exit;
}

function insertContratoFornecedor($con, $id_fornecedor, $id_contrato, $nome_arquivo, $contrato_conteudo)
{
    $insertInto = "INSERT INTO sf_contrato_fornecedor_de_despesas 
                (id_fornecedor_despesas, 
                 id_contrato, 
                 nome_arquivo,               
                 data_criacao,
                 contrato_conteudo)
            VALUES  ('$id_fornecedor', 
                        '$id_contrato', 
                        '$nome_arquivo',                    
                     getDate(),
                     '".ms_escape_string($contrato_conteudo)."' )";

    $res = odbc_exec($con, $insertInto) or exit(odbc_errormsg('Erro ao Cadastrar'));
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
