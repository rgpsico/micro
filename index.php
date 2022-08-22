<?php 

  $contrato = $_GET['contrato'] ?? '';
  $matricula = $_GET['matricula'] ?? '';
  $con = odbc_connect(
    'DRIVER={SQL Server};Server=DESKTOP-NO0HBFK\SQLEXPRESS;Database=ERP003',
    '', '');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/ico" href="../../../favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js" integrity="sha384-THVO/sM0mFD9h7dfSndI6TS0PgAGavwKvB5hAxRRvc0o9cPLohB0wb/PTA7LdUHs" crossorigin="anonymous"></script>
    <title>Assinatura</title>
</head>

<body>
 
<div id="contrato" style="padding:20px; margin:50px; text-align:left;">
    
</div>

<input type="text" id="nome_cliente">
<div class="cliente_dados">
    

</div>

<div class="assinatura-img"  style="width:200px; height:100px; margin-left:40%; padding:0;">
  <img src="Pessoas/assinatura.jpg" width="100" height="100" alt="">
</div>.

<div class="cliente"  style="padding:20px; margin:50px; text-align:left;">
   <button class='btn btn-info' id="assinar">Assinar</button>
</div>



<div class="modal fade modalAssinatura" id="modalAssinatura" tabindex="-1" role="dialog"
aria-labelledby="modalAssinatura" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assinatura">Assinatura</h5>
        <input type="hidden" id="id_cliente" value='<?php echo $_GET['id_cliente'] ?? ''; ?>'>
        <input type="hidden" id="id_contrato" value='<?php echo $_GET['contrato'] ?? ''; ?>'>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div style="text-align:center; ">
            <div class="wrapper">
                <input type='hidden' name="" id="contato_html" >

                </textarea>
                <canvas id="signature-pad" class="signature-pad col-md-12" width="700" height="300px"></canvas>
            </div>          
        </div>     
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" id="draw" data-dismiss="modal">draw</button>
        <button type="button" class="btn btn-secondary" id="clear" data-dismiss="modal">Limpar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary save-png" id="save-png">Salvar Assinatura</button>
      </div>
    </div>
  </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src='js/app.js'></script>
<script>
     $(document).ready(function(){
        getContrato(5);
        getContratoIdCliente()
    })
</script>
</html>