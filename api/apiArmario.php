
<?php
include "../../../../Connections/configini.php";
//  error_reporting(E_ALL);
// ini_set('display_errors', '1');


$order = $_REQUEST['orderBy'] ?? '';
$fieldOrder = $_REQUEST['fieldOrder'] ?? '';
$order = orderBy($order,$fieldOrder);
$cond = Where('cond');


if (isset($_REQUEST['getUserById'])):  
    $user_id = $_REQUEST['id'];  
    $records =[] ;
    $sqlUser = "SELECT  aca_arm FROM sf_usuarios_permissoes WHERE adm_usu = $user_id ";
    $cur = odbc_exec($con, $sqlUser) or die(odbc_errormsg());  
    while ($obs = odbc_fetch_array($cur)) {
        $row = array();     
        $row['aca_arm'] = $obs['aca_arm'];     
        $records[] = $row;
    }
    echo  json_encode($records);
  exit;
endif;



if (isset($_REQUEST['getServicos'])):    
    $records =[] ;
    $servicos = "SELECT * FROM sf_produtos WHERE ck_validade_serv = 1 ";
    $cur = odbc_exec($con, $servicos) or die(odbc_errormsg());  
    while ($obs = odbc_fetch_array($cur)) {
        $row = array();
        $row['id'] = $obs['conta_produto'];
        $row['descricao'] = utf8_decode($obs['descricao']);     
        $records[] = $row;
    }
    echo  json_encode($records);
  exit;
endif;


if (isset($_REQUEST['getFinanceiroArmario'])):
    $matricula = $_REQUEST['matricula_id'];    
    
      $sql = "SELECT DISTINCT
      p.tipo AS tipoProduto, 
      rel.id_produto  AS prod_rel, 
      cliente.id_fornecedores_despesas AS matricula,
      venda.id_venda AS venda,
      venda.data_venda as data_venda,
      cliente.razao_social AS nome,
      vi.produto AS id_serv,
      p.descricao AS servico,
      p.tipo AS tipo,
      IIF((SELECT TOP 1 id_venda FROM sf_fornecedores_despesas_armario AS a WHERE a.matricula = $matricula
		 AND a.desvinculado_em IS NULL ) = venda.id_venda,'alugado','nao alugado') AS situacao_armario	 
         FROM sf_vendas_itens AS vi
     LEFT JOIN sf_armario_relacao AS rel ON rel.id_produto = vi.produto
     LEFT JOIN sf_vendas AS venda ON venda.id_venda = vi.id_venda
     LEFT JOIN sf_fornecedores_despesas AS cliente ON cliente.id_fornecedores_despesas = venda.cliente_venda
     LEFT JOIN sf_produtos AS p ON p.conta_produto = rel.id_produto 
     WHERE 
	   p.ck_validade_serv = 1 
	  AND venda.cliente_venda = $matricula
	  AND p.tipo = 'S' 
      AND venda.dt_estorno is null 
    
     ";
    
    $cur = odbc_exec($con, $sql) or die(odbc_errormsg());
    $count = odbc_num_rows($cur);  
    
    if($count == 0){
        echo 0;
        exit;
    }
    while ($obs = odbc_fetch_array($cur)) {
        $row = array();
        $row['id'] = $obs['venda'];
        $row['produto'] = $obs['produto'];
        $row['nomeProduto'] = $obs['servico']; 
        $row['produto'] = $obs['id_serv'];  
        $row['data_venda'] = date("d/m/Y", strtotime($obs['data_venda']));  
        $row['situacao_armario'] = $obs['situacao_armario'];  
  
        $records[] = $row;
        
    }
    echo  json_encode($records);
  exit;
endif;


if (isset($_REQUEST['getFinanceiroArmarioVinculado'])):    
    $records =[] ;
    $sql = "SELECT TOP 1 p.descricao as nomeProduto, i.id_venda as id_venda, v.data_venda as data_venda, i.produto as produto 
    FROM sf_vendas as v 
    LEFT JOIN sf_vendas_itens as i on(i.id_venda = v.id_venda)
    LEFT JOIN sf_produtos as p on(i.id_venda = p.conta_produto)
    LEFT JOIN sf_fornecedores_despesas_armario as desarm on(i.id_venda = desarm.id_venda)
    WHERE p.ck_validade_serv = 1 
    ";
    $cur = odbc_exec($con, $sql) or die(odbc_errormsg());  
    $count = odbc_num_rows($cur);
      
    while ($obs = odbc_fetch_array($cur)) {
        $row = array();
        $row['id'] = $obs['id_venda'];
        $row['data_venda'] =date("d/m/Y", strtotime($obs['data_venda']));  
        $row['produto'] = $obs['produto'];    
        $row['nomeProduto'] = $obs['nomeProduto']; 
        $records[] = $row;
        
    }
    echo  json_encode($records);
  exit;
endif;



if (isset($_REQUEST['getFinanceiroArmarioVinculadoById'])): 
    $matricula = $_REQUEST['matricula'];   
    $records =[] ;
    $sql = "SELECT TOP 1 p.descricao as nomeProduto, i.id_venda as id_venda, v.data_venda as data_venda, i.produto as produto 
    FROM sf_vendas as v 
    LEFT JOIN sf_vendas_itens as i on(i.id_venda = v.id_venda)
    LEFT JOIN sf_produtos as p on(i.id_venda = p.conta_produto)
    LEFT JOIN sf_fornecedores_despesas_armario as desarm on(i.id_venda = desarm.id_venda)
    WHERE p.ck_validade_serv = 1 AND desarm.matricula = $matricula
    ";
    $cur = odbc_exec($con, $sql) or die(odbc_errormsg());  
    $count = odbc_num_rows($cur);
      
    while ($obs = odbc_fetch_array($cur)) {
        $row = array();
        $row['id'] = $obs['id_venda'];
        $row['data_venda'] =date("d/m/Y", strtotime($obs['data_venda']));  
        $row['produto'] = $obs['produto'];    
        $row['nomeProduto'] = $obs['nomeProduto']; 
        $records[] = $row;
        
    }
    echo  json_encode($records);
  exit;
endif;


/*sf_armarios*/
if (isset($_REQUEST['getAllByStatus'])): 
    $status = $_REQUEST['status'];
    $queryGetStatus = "SELECT * FROM sf_armario where status = $status";
    echo getField($con, $queryGetStatus);
  exit;
endif;


if (isset($_REQUEST['getAll'])): 
     $queryGetAll = "SELECT * FROM sf_armario  WHERE status = 0 $order";
     echo getField($con, $queryGetAll);
   exit;
endif;




if (isset($_REQUEST['getById'])):  
     $id = $_REQUEST['id'];
     //$situacaoArmario = $_REQUEST['situacao'] == '0' ? 'AND af.desvinculado_em is NULL' : 'AND af.desvinculado_em is not NULL';
    
    $queryGetById = "SELECT TOP 1 *, 
    ar.id as armario_id, CONCAT(ar.id,'', SUBSTRING(ar.genero,1,1), SUBSTRING(ar.tamanho,1,1)   ) as nome,
    alunos.razao_social as nome_aluno,
    IIF((SELECT TOP 1 id_venda FROM sf_fornecedores_despesas_armario AS a WHERE a.matricula = $id
        AND a.desvinculado_em IS NULL ) = id_venda,'alugado','nao alugado') AS situacao_armario	
    FROM sf_armario as ar 
    LEFT join sf_fornecedores_despesas_armario as af on (ar.id = af.armario_id)
    LEFT join sf_fornecedores_despesas as alunos on (alunos.id_fornecedores_despesas = af.armario_id)
    WHERE af.matricula = $id
    AND af.desvinculado_em is NULL
    order by af.id DESC";
  
    $cur = odbc_exec($con, $queryGetById) or die(odbc_errormsg());  
    while ($obs = odbc_fetch_array($cur)) {
        $row = array();    
        $row['id'] = $obs['armario_id']; 
        $row['armario_id'] = $obs['armario_id'];
        $row['id_fornecedor_despesa'] = $obs['matricula'];
        $row['tamanho'] = $obs['tamanho'];
        $row['genero'] = $obs['genero'];
        $row['nome'] = $obs['nome'];
        $row['validade'] = $obs['validade'];
        $row['validade_js'] = date("Y-m-d", strtotime($obs['validade']));  
        $row['id_venda'] = $obs['id_venda'];
        $row['status'] = $obs['status'];
        $row['nome_aluno'] = $obs['nome_aluno'];
        $row['situacao_armario'] = $obs['situacao_armario'];
        $records[] = $row;
    }
    verificarSeVendaExisti($con, $row['id_venda'], $row['armario_id'], $id);   
    verificarSeVendaFoiEstornada($con, $row['id_venda'], $row['armario_id'], $id);
    desvincularArmarioPelaValidade($con, $row['validade'], $row['armario_id'], $id);
    echo  json_encode($records);
    exit;
endif;






if (isset($_REQUEST['getByName'])):   
    $nome = $_REQUEST['nome_aluno'];
    $where = $_REQUEST['where']; 
    return getField($cur, $queryGetByName);
endif;


if (isset($_REQUEST['store'])):
    $genero = $_REQUEST['genero'] ?? '';
    $tamanho = $_REQUEST['tamanho'] ?? '';
    $apelido = $_REQUEST['apelido'] ?? '';
    $status = intval($_REQUEST['status']) ?? '';
    $tabela = 'sf_armario';
    $campo_id = 'id';
    $campos = 'genero, tamanho, status, nome_personalizado ';
    $explode = explode(', ', $campos);
  
    $queryInsert = "INSERT INTO $tabela ($campos)  VALUES ('$genero', '$tamanho',  '$status','$apelido')";
    $cur = odbc_exec($con, $queryInsert) or die(odbc_errormsg('Armário não pode ser cadastrado')); 
    $count = odbc_num_rows($cur);   
    if($count == 1){
      echo 1;    
    } else {
       echo 0;
    }
  

    
endif;


if (isset($_REQUEST['update'])):
    $id = $_REQUEST['id'];
    $genero = $_REQUEST['genero'] ?? '';
    $tamanho = $_REQUEST['tamanho'] ?? '';
    $apelido = $_REQUEST['apelido'] ?? '';
    $status = intval($_REQUEST['status']) ?? '';
       
     echo $queryUpdate = "UPDATE  sf_armario SET  tamanho='$tamanho', genero='$genero', nome_personalizado='$apelido' WHERE  id='$id'";
     $cur = odbc_exec($con, $queryUpdate) or die(odbc_errormsg('Erro ao Atualizar'));
     
    if($curl){
        echo "Atualizado com sucesso";
        return;
    }  
endif;

if (isset($_REQUEST['updateStatusArmario'])):
    $id = $_REQUEST['id_armario'];
    $status = $_REQUEST['status'];
    $id_venda = $_REQUEST['id_venda'];
    $matricula = $_REQUEST['matricula_id'];   
    $queryUpdate = "UPDATE  $tabela SET status='$status' WHERE  $campo_id='$id'";
    $cur = odbc_exec($con, $queryUpdate) or die(odbc_errormsg('Erro ao Atualizar'));
    
   if($curl){
       echo "Atualizado com sucesso";
       return;
   }  
endif;


/*UPDATE VINCULO*/
if (isset($_REQUEST['updateVinculoArmario'])):
   $validade = $_REQUEST['data_vencimento_vinculo']; 
   $armario_id = $_REQUEST['armario_id'];
   $id_venda = $_REQUEST['id_venda'];
   $matricula = $_REQUEST['matricula_id'];   

   $queryUpdateVinculo = "UPDATE  sf_fornecedores_despesas_armario SET validade='".date('Y-m-d',strtotime($validade))."' WHERE  armario_id='$armario_id'";  
   
   logs_armario($con, 'Alterar data de validade do armario '.$armario_id.' tela de cliente', 'A', 'sf_fornecedores_despesas_armario', $id_venda,  $matricula);
   
   $cur = odbc_exec($con, $queryUpdateVinculo) or die(odbc_errormsg('Erro ao Atualizar'));
    
   if($curl){
       echo "Atualizado com sucesso";
       return;
   }  
endif;


if (isset($_REQUEST['desvincularArmario'])):
    $matricula_id = $_REQUEST['matricula_id']; 
    $armario_id = $_REQUEST['armario_id'];
    $id_venda = $_REQUEST['id_venda'];

    logs_armario($con, 'desvincular armario '. $armario_id.'','R', 'sf_fornecedores_despesas_armario', $id_venda,  $matricula_id);
    $queryUpdateDesvinculo = "UPDATE  sf_fornecedores_despesas_armario SET desvinculado_em=getDate() WHERE   matricula ='$matricula_id' ";  
    $cur = odbc_exec($con, $queryUpdateDesvinculo) or die(odbc_errormsg('Erro ao Atualizar'));
    
   if($curl){
       echo "Armário desvinculado com sucesso";
       return;
   }  
endif;


if (isset($_REQUEST['insertVinculo'])): 
    $id = $_REQUEST['id_vinculo'];
    $validade = date('Y-m-d',strtotime($_REQUEST['data_vencimento_vinculo'])); 
    $armario_id = $_REQUEST['armario_vinculo_id'];  
    $matricula = $_REQUEST['id_cliente'];   
    $id_venda = $_REQUEST['id_venda'];
   
    desvincularArmarioByCliente($con, $matricula);   
    
   $insertVinculoSql = "INSERT INTO  sf_fornecedores_despesas_armario (validade, data_cad, armario_id, matricula, id_venda) 
                          VALUES ('$validade', getDate(), '$armario_id', '$matricula', '$id_venda')";  
    $cur = odbc_exec($con, $insertVinculoSql) or die(odbc_errormsg('Erro ao Atualizar'));
    
   if($cur){
    logs_armario($con, 'INCLUSAO Armario '.$armario_id.'', 'I', 'sf_fornecedores_despesas_armario', $id_venda,  $matricula);
       echo "Cadastrado com sucesso";
       return;
   }  
endif;



if (isset($_REQUEST['destroyVinculo'])):
    $id = $_REQUEST['id_vinculo'];
    $queryDelete = "DELETE  sf_fornecedores_despesas_armario WHERE  id='$id'"; 
    $cur = odbc_exec($con, $queryDelete) or die(odbc_errormsg()); 
    if($cur){
        echo "Excluido com sucesso";
     return true;
     exit;
    }
    return false;
endif;


if (isset($_REQUEST['getAllForncedorDespesasArmarios'])):  
    $id = intval(@$_REQUEST['id']);
    $order = @$_REQUEST['order'];
    
    // desvincularArmario($con, $armario_id, $matricula_id);
     $queryGetById = "SELECT 
    *,
    ar.id AS armario_id, 
    razao_social as nomeFornecedor,
    CONCAT(ar.id, '', SUBSTRING(ar.genero, 1, 1), SUBSTRING(ar.tamanho, 1, 1)) AS nome
    FROM sf_armario AS ar
    INNER JOIN sf_fornecedores_despesas_armario AS af ON (ar.id = af.armario_id)
    LEFT JOIN sf_fornecedores_despesas as fornecedor ON (af.matricula = fornecedor.id_fornecedores_despesas) 
    ORDER BY af.id DESC
      ";
  
    $cur = odbc_exec($con, $queryGetById) or die(odbc_errormsg());  

    
    while ($obs = odbc_fetch_array($cur)) {
        $row = array();     
        $row['id'] = $obs['id'];
        $row['armario_id'] = $obs['armario_id'];
        $row['id_fornecedor_despesa'] = $obs['matricula'];
        $row['tamanho'] = $obs['tamanho'];
        $row['genero'] = $obs['genero'];
        $row['nome'] = $obs['nome'];
        $row['id_venda'] = $obs['id_venda'];   
        $row['data_cad'] = date("d/m/Y", strtotime($obs['data_cad']));
        $row['validade'] = date("d/m/Y", strtotime($obs['validade']));
        $row['data_cad_us'] = date("Y-m-d", strtotime($obs['data_cad']));
        $row['validade_us'] = date("Y-m-d", strtotime($obs['validade']));   
        $row['nome_usuario'] = utf8_decode($obs['nomeFornecedor']); 
        $row['apelido'] = utf8_decode($obs['nome_personalizado']); 
        $records[] = $row;
    }
    echo  json_encode($records);
    exit;
endif;






/*FINAL UPDATE VINCULO*/



/*TABELA sf_fornecedores_armario*/
if (isset($_REQUEST['destroy'])):
    $id = $_REQUEST['id'];
    $queryDelete = "DELETE  sf_armario WHERE  id='$id'"; 
    $cur = odbc_exec($con, $queryDelete) or die(odbc_errormsg()); 
    if($cur){
        echo "Excluido com sucesso";
     return true;
     exit;
    }
    return false;
endif;




if (isset($_REQUEST['insertRelacao'])):
    $id_produto = $_REQUEST['id_servico'];
   
    $select = "SELECT COUNT(*) AS total FROM sf_armario_relacao WHERE id_produto= $id_produto"; 
    $res = odbc_exec($con, $select) or die(odbc_errormsg('Erro ao Atualizar'));
    $obs = odbc_fetch_array($res);
    echo $obs['total'];    

 
   if($obs['total'] == 0){   
    $insertInto = "INSERT INTO  sf_armario_relacao  (id_produto) VALUES ($id_produto)"; 
    $res = odbc_exec($con, $insertInto) or die(odbc_errormsg('Erro ao Atualizar'));
    exit;
   } 


endif;


function getField($con, $query){
    $records =[] ;
    $cur = odbc_exec($con, $query) or die(odbc_errormsg());  
    while ($obs = odbc_fetch_array($cur)) {
        $row = array();
        $row['id'] = $obs['id'];
        $row['genero'] = $obs['genero'];
        $row['tamanho'] = $obs['tamanho'];
        $row['valor'] = $obs['valor'];
        $row['status'] = $obs['status'];
        $row['nomeArmario'] = $obs['id'].''.substr($obs['genero'],0,1).''.substr($obs['tamanho'],0,1);
        $row['apelido'] = $obs['nome_personalizado'];
        $records[] = $row;
    }
    echo  json_encode($records);
}



function ms_escape_string($data) {
    if ( !isset($data) or empty($data) ) return '';
    if ( is_numeric($data) ) return $data;

    $non_displayables = array(
        '/%0[0-8bcef]/',            // url encoded 00-08, 11, 12, 14, 15
        '/%1[0-9a-f]/',             // url encoded 16-31
        '/[\x00-\x08]/',            // 00-08
        '/\x0b/',                   // 11
        '/\x0c/',                   // 12
        '/[\x0e-\x1f]/'             // 14-31
    );
    foreach ( $non_displayables as $regex )
        $data = preg_replace( $regex, '', $data );
    $data = str_replace("'", "''", $data );
    return $data;
}


function orderBy($order, $field){
    switch ($order) {
        case 1:
       return  "Order by $field Desc";
        break;

        case 2:
        return "Order by $field ASC";
        break;               
    }

}

function Where($where){
    if ($where == 'todos') {
        $cond = '';
    }

    if ($where == 'ativos') {
        $cond = 'AND data_de_exclusao IS NULL';
    }

    if ($where == 'inativos') {
        $cond = 'AND data_de_exclusao IS NOT NULL';
    }
}



function updateTableFornecedorDespesasArmario($con, $data_venc, $id_fornecedor){
    $id_fornecedor = $_REQUEST['id'];
       
   $queryUpdate = "UPDATE  sf_fornecedores_despesas_armario SET  data_venc='$data_venc', data_cad=getDate()  WHERE  id_fornecedor_despesa='$id_fornecedor'";
 
   $res = odbc_exec($con, $queryUpdate) or die(odbc_errormsg('Erro ao Atualizar'));
   
    if($res){
        echo "Atualizado com sucesso";
        return;
    } 

}

function logs_armario($con, $log, $acao, $tabela, $item_id,  $matricula) {
        $logsArm = "INSERT INTO sf_logs (tabela, id_item, usuario, acao, descricao,  data, id_fornecedores_despesas)
        values ('$tabela', " . $item_id . ", '" . $_SESSION["login_usuario"] . "', '$acao', '".$log."', GETDATE(), '$matricula')";
    $res = odbc_exec($con, $logsArm) or die(odbc_errormsg('Erro ao Atualizar'));
    $count = odbc_num_rows($res);
        if($count > 0) {
          return true;                 
        }   
}

function temArmario($con, $matricula, $armario_id){
   $temArm = "SELECT * FROM sf_fornecedores_despesas_armario WHERE matricula = $matricula AND armario_id=$armario_id "; 
   $res = odbc_exec($con, $temArm) or die(odbc_errormsg('Erro ao Atualizar'));
   $count = odbc_num_rows($res);
        if($count > 0){
          return true;                 
        }       
}




function updateStatusArmario($con, $status, $armarios_id, $cliente_id)
{   
 
   if($armarios_id != null ){      
      $updateStatusArmario = "UPDATE sf_armario SET status='$status' WHERE  id= $armarios_id"; 
      $res = odbc_exec($con, $updateStatusArmario);    
      $count = odbc_num_rows($res); 
           if($count == 0){
             echo "O status do armário não pode ser atualizado";                             
         }
         echo  $cliente_id;
         exit;       
      }     
      echo  $cliente_id;   
      exit;       
}

function updateDataFornecedorArmario($con, $data_venc, $armarios_id)
{   
    $updateStatusArmario = "UPDATE sf_fornecedores_despesas_armario SET validade='$data_venc'  WHERE  id=$armarios_id"; 
    $res = odbc_exec($con, $updateStatusArmario) or die(odbc_errormsg('Erro ao Atualizar'));
        if($res){
           return true;                    
        }         
}



function updateRelacaoArmario($con, $id_produto)
{   
    $updateStatusArmario = "UPDATE sf_armario_relacao SET id_produto='$id_produto'  WHERE  matricula=$matricula"; 
    $res = odbc_exec($con, $updateStatusArmario) or die(odbc_errormsg('Erro ao Atualizar'));
        if($res){
           return true;                    
        }         
}



function insertRelacao($con, $id_produto)
{   
    $insertInto = "INSERT INTO  sf_armario_relacao  (id_produto) VALUES ($id_produto)"; 
    $res = odbc_exec($con, $insertInto) or die(odbc_errormsg('Erro ao Atualizar'));
        if($res){
           return true;                    
        }         
}


function temProdutoRelacao($con, $id_produto)
{   
    $select = "SELECT COUNT(*) AS total FROM sf_armario_relacao WHERE id_produto= $id_produto"; 
    $res = odbc_exec($con, $select) or die(odbc_errormsg('Erro ao Atualizar'));
    $obs = odbc_fetch_array($res);
    echo $obs['total'];    
}



   
function inserirArmarioFornecedoresDespesas($con, $data_venc, $id_fornecedor, $armario_id, $id_venda){ 
          
         $insertArm = "INSERT INTO  sf_fornecedores_despesas_armario (validade, data_cad, matricula, armario_id, id_venda) 
         VALUES ('$data_venc', getDate(), $id_fornecedor, $armario_id, $id_venda)"; 
         $res = odbc_exec($con, $insertArm) or die(odbc_errormsg('Erro ao Atualizar'));  
         if ($res) {
            return true;               
         }         
   } 


   
function pegarTodosVinculosCliente($con, $matricula) { 
     $select = "SELECT TOP 1 * from sf_fornecedores_despesas_armario WHERE matricula = $matricula"; 
    $res = odbc_exec($con, $select) or die(odbc_errormsg('Erro ao Atualizar'));  
    $obs = odbc_fetch_array($res);
    echo $obs['id_venda'];
  
} 

function desvincularArmarioPelaValidade($con, $validade, $armario_id, $matricula_id){
    $hoje = date('Y-m-d');    
    if($validade < $hoje){      
        $queryUpdateDesvinculo = "UPDATE  sf_fornecedores_despesas_armario SET desvinculado_em=getDate() WHERE  armario_id='$armario_id' AND matricula ='$matricula_id' ";  
        $curl = odbc_exec($con, $queryUpdateDesvinculo) or die(odbc_errormsg('Erro ao Atualizar'));    
        
        if($curl) {
           echo "Armário desvinculado com sucesso";
            return;
        } 
    }
}

function desvincularArmarioByClienteEArmario($con, $armario_id, $matricula_id){  
        $queryUpdateDesvinculo = "UPDATE  sf_fornecedores_despesas_armario SET desvinculado_em=getDate() WHERE  armario_id='$armario_id' AND matricula ='$matricula_id' ";  
        $curl = odbc_exec($con, $queryUpdateDesvinculo) or die(odbc_errormsg('Erro ao Atualizar'));   
        
        if($curl) {
           echo "Armário desvinculado com sucesso";
            return;        
        }
}


function desvincularArmarioByCliente($con, $matricula_id){  
    echo $queryUpdateDesvinculo = "UPDATE  sf_fornecedores_despesas_armario SET desvinculado_em=getDate() WHERE   matricula ='$matricula_id' ";  
    $curl = odbc_exec($con, $queryUpdateDesvinculo) or die(odbc_errormsg('Erro ao Atualizar'));   
    
    if($curl) {
       echo "Armário desvinculado com sucesso";
        return;        
    }
}

function verificarSeVendaFoiEstornada($con, $id_venda, $id_armario, $matricula){
    $sql = "SELECT dt_estorno from sf_vendas where id_venda = $id_venda AND dt_estorno is NOT null";
    $cur = odbc_exec($con, $sql) or die(odbc_errormsg());  
    $count = odbc_num_rows($cur);
      if($count == 1){
        updateStatusArmario($con, '0', $id_armario, $matricula);
        desvincularArmarioByClienteEArmario($con, $id_armario, $matricula);
      
      }   
}

function verificarSeVendaExisti($con, $id_venda, $id_armario, $matricula){
    $sql = "SELECT dt_estorno from sf_vendas where id_venda = $id_venda";
    $cur = odbc_exec($con, $sql) or die(odbc_errormsg());  
    $count = odbc_num_rows($cur);
      if($count == 0){       
        updateStatusArmario($con, '0', $id_armario, $matricula);
        desvincularArmarioByClienteEArmario($con, $id_armario, $matricula);      
      }   
}




if (isset($_REQUEST['getVendaIdByCliente'])):
    $matricula = $_REQUEST['matricula_id'];    
    $select = "SELECT TOP 1 * from sf_fornecedores_despesas_armario WHERE matricula = $matricula"; 
    $res = odbc_exec($con, $select) or die(odbc_errormsg('Erro ao Atualizar'));  
    while ($obs = odbc_fetch_array($res)) {
        $row = array();
        $row['id'] = $obs['id_venda'];
        $records[] = $row;        
    }
    echo json_encode($records);
  exit;
endif;
  



