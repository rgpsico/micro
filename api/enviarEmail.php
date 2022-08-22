<?php 


require_once __DIR__  . '../../../../../Connections/configini.php';
require_once '../../../../api/utilidades/email/util.enviar.email.php';
require_once '../../../../api/modulo/cadastro/cliente/v2/_commom/libs/PHPMailer/PHPMailer.php';
require_once '../../../../api/modulo/cadastro/cliente/v2/_commom/libs/PHPMailer/SMTP.php';
require_once '../../../../api/modulo/cadastro/cliente/v2/_commom/libs/PHPMailer/PHPMailerException.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

 $contrato_id = $_REQUEST['contrato'] ;
 $cliente_id = $_REQUEST['cliente_id'] ;
 $url = "v2/Modulos/Financeiro/assinaturadigital/index.php?id_cliente=".$cliente_id."&id_contrato=".$contrato_id;

function url($link)
{
    if (strcmp($link,"127.0.0.1") == 0  ) {
          $url_atual = 'http://127.0.0.1/micro/assinatura/';
    
        } elseif (strcmp($link,"https://teste.mufitness.com.br/v2/" )  == 0) {
         $url_atual = 'https://teste.mufitness.com.br/v2/';
    
    } else {
          $url_atual = 'https://app.mufitness.com.br/v2/';
    }
    return $url_atual;
  
}
$url = $_SERVER['REMOTE_HOST'];





$linkDoContratoDoCliente =  url($url)."Modulos/Financeiro/assinaturadigital/index.php?id_cliente=".$cliente_id."&contrato=".$contrato_id;
$mail = new PHPMailer(true); //Argument true in constructor enables exceptions
	// ----------------- DISPARO DE EMAIL ------------------- \\
    $utilEnviarEmailConfig = new UtilEnviarEmailConfig();
    $utilEnviarEmail = new UtilEnviarEmail($con);
    $utilEnviarEmailConfig->email_host = 'smtplw.com.br';
    $utilEnviarEmailConfig->email_porta = 587;
    $utilEnviarEmailConfig->email_login = 'sivis';
    $utilEnviarEmailConfig->email_senha = 'QAWJnGxi3638';
    $utilEnviarEmailConfig->email_nome = 'roger neves';
    $utilEnviarEmailConfig->email_remetente = 'programador3@sivis.com.br';

    $utilEnviarEmailConfig->assunto =  'Contrato';;
    $utilEnviarEmailConfig->conteudo = "<h1>Segue em anexo o contrato</h1>
                                        <a href='teste'>LINK contrato</a>";

    $utilEnviarEmailConfig->conteudo = "<br><p>Segue o email para assinatura do contrato ".
                                        "Para ver o pretendente, clique no link baixo. <br> ".
                                        "<a href='$linkDoContratoDoCliente".
                                        "'>Assinar documento</a>".
                                        "</p>";


    $utilEnviarEmailConfig->enderecos_email[] = array('rgyr2010@hotmail.com', 'rgyr2010@hotmail.com');
    $utilEnviarEmailConfig->enderecos_email_copia_oculta = array(
        ['programador3@sivis.com.br','email teste'],
        ['barbarabiancov@gmail.com','email teste'],
        ['telmo@microuniversity.com.br','email teste']
    );
    try {
        if ($utilEnviarEmail->envialEmailManual($utilEnviarEmailConfig, $info_erro)) {
            echo 'Yes';
        }
    } catch (\Throwable $th) {
        //throw $th;
    }