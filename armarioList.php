<?php
include '../../Connections/configini.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <title>Micro University</title>
        <link rel="icon" type="image/ico" href="../../favicon.ico"/>
        <link rel="stylesheet" type="text/css" href="../../css/stylesheets.css"/>
        <link rel="stylesheet" type="text/css" href="../../css/main.css"/>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <link rel="stylesheet" href="armario/style/armario.css">    

        <script type="text/javascript" src="../../js/plugins/jquery/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/bootbox/bootbox.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0-alpha.1/axios.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
        <link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>

        <!-- jQuery Library -->
   
        <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

        <style>
            #cadastro_armario_menu{
                margin-left:2px;
                border:1px solid #eeeeee;
                border-bottom:none;
                            
            }
            #cadastro_armario_menu :active{
                background: #eeeeee; 
                opacity: 1.0;         
            }


            #historico_armario_menu{
                margin-left:1px;
                border:1px solid #eeeeee;
                border-bottom:none;
                border-left:none;
                     
            }
            #historico_armario_menu :active{
                background:;     
               
            }
     
        </style>

    </head>
    <body>

    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg modal-micro in" id="modal_armario"  
    tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="false">
    <div class="modal-conteudo">
        <div class="modal-header">          
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="width:20px; font-weight:bold; color:#000;">
                <span aria-hidden="true" style="color:#000; font-weight:bold; font-size:20px; opacity:1;" class="icon-fechar-header abrir-segundo-modal">
                <img src="../../img/fechar.png" width="20" height="20"></span>
            </button>
            <h5 class="modal-title modal-titulo-aviso">NOTIFICAÇÂO: AVISO</h5>

        </div>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body modal-micro-body" >
                    
                </div>
                <div class="modal-footer modal-micro-footer">
                <button type="button" class="btn btn-secondary btn-fechar-modal-micro"                    
                      data-dismiss="modal">Fechar
                </button>
                    <button type="button" class="btn btn-secondary btn-salvar-modal-micro" style="margin:0; margin-left:8px;"                    
                     >Salvar
                </button>

                </div>
            </div>
        </div>
    </div>
</div>
        <?php if ($imprimir == 0) { ?>
            <div id="loader"><img src="../../img/loader.gif"/></div>
        <?php } ?>
        <div class="wrapper">
            <?php

                include '../../menuLateral.php';

            ?>
            <div class="body">
                <?php
                if ($imprimir == 0) {
                    include '../../top.php';
                }
                ?>

                <div class="content">
                 
                 
                    <div class="page-header" <?php echo $visible; ?>>
                        <div class="icon"> <span class="ico-arrow-right"></span></div>
                        <h1>Academia<small>Armarios</small></h1>
                    </div>
               
                    <div class="row-fluid" <?php echo $visible; ?>>
                        <div class="span12">
                            <div class="block">
                                <button id="botao_cadastrar" class="button button-green btn-primary" type="button" ><span class="ico-file-4 icon-white"></span></button>
                                <button id="imprimir_armario" class="button button-blue btn-primary" type="button" ><span class="ico-print icon-white"></span></button>
                               
                                <select name="" id="servicos" style="width:200px;">
                                    <option value="" selected>Serviços</option>
                                </select>

                                <select name="" id="filtro_status" style="width:200px;">
                                    <option value="0" selected>Disponível </option>
                                    <option value="1">Ocupado </option>
                                </select>
                                <input id="imprimir" name="imprimir" type="hidden" value="<?php echo $_GET['imp']; ?>"/>
                                <button class='btn btn-success'  id="bt_buscar_armario" style="float:right; height:25px;"> Buscar</button>
                                <input type="text" name="text_buscar_armario" id="text_buscar_armario" style="width:200px; float:right; margin-right:5px;" placeholder="buscar">
                                
                            </div>
                        <div class="col-12" style="margin-top:80px;">
                            <div class="ata-fluid">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"  id="cadastro_armario_menu" >
                                        <a class="nav-link active" aria-current="page"  href="#">Cadastro de Armários</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" id="historico_armario_menu">Histórico de vinculos</a>
                                    </li>                          
                                </ul>
                             </div>                         
                        </div>
                    </div>
                        
                    </div>
                    <div style="clear:both"></div>
                    <section id="cadastro_armario" style="border:10px solid #fff;" >
                                <div style="border:1px solid #fff;" class="boxhead" <?php echo $visible; ?>>
                                    <div class="boxtext">Armários </div>
                                </div>
                            <div>
                                    <?php
                                    if ($imprimir == 1) {
                                        $titulo_pagina = 'RELATÓRIO DE CONSULTA<br/>SERVIÇOS';
                                        include '../Financeiro/Cabecalho-Impressao.php';
                                    }
                                    ?>
                                <table class="table" style="float:none" cellpadding="0" cellspacing="0" width="100%" id="armario">
                                    <thead>
                                        <tr>
                                            <th width="8%">Status <i class="fa-solid fa-up-long th-icon valor_order"></i></th>       
                                            <th width="10%">Sequencial <i class="fa-solid fa-up-long th-icon id_order" style="cursor:pointer;" ></i></th>
                                            <th width="20%">Nome<i class="fa-solid fa-up-long th-icon genero_order"></i></th>
                                            <th width="20%">Apelido<i class="fa-solid fa-up-long th-icon genero_order"></i></th>
                                            <th width="20%">Gênero<i class="fa-solid fa-up-long th-icon genero_order"></i></th>
                                            <th width="10%">Tamanho<i class="fa-solid fa-up-long th-icon tamanho_order"></i></th>
                                            <!-- <th width="10%">Valor <i class="fa-solid fa-up-long th-icon valor_order"></i></th>      -->
                                                                                                
                                            <?php if ($imprimir == 0) { ?>
                                                <th width="20%"><center>Ação</center></th>
                                    <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody id="listar_armario">
                                    
                                    </tbody>
                                
                                </table>
                                <div style="clear:both"></div>
                                <div class="footer-table" style="height:30px; padding:10px;">
                                    <tfoot>
                                            <tr>Visualização do registro 1 ao 2 de 2</tr>
                                            <tr></tr>
                                            <tr></tr>
                                            <tr>
                                            <div class="dataTables_paginate paging_full_numbers" 
                                            id="tblHorarios_paginate">
                                            <a tabindex="0" 
                                            class="first paginate_button paginate_button_disabled"
                                            id="tblHorarios_first">Primeiro</a>
                                            
                                            <a tabindex="0" class="previous paginate_button paginate_button_disabled" 
                                            id="tblHorarios_previous">Anterior</a>
                                            <span><a tabindex="0" class="paginate_active">1</a>
                                        </span>
                                            <a tabindex="0" class="next paginate_button paginate_button_disabled" 
                                            id="tblHorarios_next">Próximo</a>
                                            <a tabindex="0" class="last paginate_button paginate_button_disabled" 
                                            id="tblHorarios_last">Último</a>
                                        </div>

                                            </tr>
                                    </tfoot>
                                </div>
                            </div>
                       
                </section>


                <section id="historico_armario" style="display:none; border:10px solid #fff;">
          
                    <div class="boxhead" style="border:1px solid #fff;">
                        <div class="boxtext">Histórico Armário </div>
                    </div>
                <div class="container" style=" width:100%; background:#fff;";>             
                <table class="table" id="empTable" style="float:none" cellpadding="0" cellspacing="0" width="100%" id="armario">
                                    <thead>
                                        <tr>
                                            <th width="8%">Id <i class="fa-solid fa-up-long th-icon valor_order"></i></th>
                                            <th width="12%">Matricula <i class="fa-solid fa-up-long th-icon id_order" style="cursor:pointer;" ></i></th>       
                                             <th width="18%">Cliente <i class="fa-solid fa-up-long th-icon id_order" style="cursor:pointer;" ></i></th>
                                            <th width="10%">Data de Cadastro <i class="fa-solid fa-up-long th-icon genero_order"></i></th>
                                            <th width="10%">Armário id <i class="fa-solid fa-up-long th-icon genero_order"></i></th>
                                            <th width="10%">Venda id <i class="fa-solid fa-up-long th-icon genero_order"></i></th>
                                            <th width="10%">Validade <i class="fa-solid fa-up-long th-icon tamanho_order"></i></th>                                           
                                                                                                
                                            <?php if ($imprimir == 0) { ?>
                                                <th width="20%"><center>Ação</center></th>
                                    <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody id="historico_armario_row"></tbody>
                                
                                </table>

                            </div>

              
                           
                </section>
            </div>
        </div>
        <div class="dialog" id="source" title="Source"></div>
        <link rel="stylesheet" type="text/css" href="../../fancyapps/source/jquery.fancybox.css?v=2.1.4" media="screen" />
        <script type="text/javascript" src="../../js/plugins/jquery/jquery-ui-1.10.1.custom.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/jquery/jquery-migrate-1.1.1.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/jquery/globalize.js"></script>
        <script type="text/javascript" src="../../js/plugins/other/excanvas.js"></script>
        <script type="text/javascript" src="../../js/plugins/other/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/cookies/jquery.cookies.2.2.0.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/sparklines/jquery.sparkline.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="../../js/plugins/uniform/jquery.uniform.min.js"></script>
        <script type="text/javascript" src="../../js/plugins.js"></script>
        <script type="text/javascript" src="../../js/charts.js"></script>
        <script type="text/javascript" src="../../js/actions.js"></script>
        <script type="text/javascript" src="armario/js/armario.js"></script>
        

          

        <script type="text/javascript">
              function Export() {
                $("#table").table2excel({
                        filename: "file.xls"
                 });
            }
            
          
            
            
            function refresh() {
                var oTable = $("#armario").dataTable();
                oTable.fnReloadAjax(finalURL(0));
            }



            function FecharBox() {
                refresh();
                $("#newbox").remove();
            }

        <?php if ($imprimir == 1) { ?>  
            $(window).load(function () {
                $(".body").css("margin-left", 0);
                $("#example_length").remove();
                $("#example_filter").remove();
                $("#example_paginate").remove();
                $("#formPQ > th").css("background-image", "none");
            });
        <?php } ?>
        </script>
        <style type="text/css">
            .fancybox-custom .fancybox-skin {
                box-shadow: 0 0 50px #069;
            }
        </style>        
        <?php odbc_close($con); ?>
    </body>
    <script type="text/javascript" charset="utf8" 
  src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
</html>
</script>
