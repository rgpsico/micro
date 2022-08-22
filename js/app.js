// 1) Assinatura digital deve ser armazenada numa sub-pasta dentro de 'Pessoas' (servidor) e dentro da Pasta Assinatura,
//  terão sub-pastas por matricula e dentro detas, a assinatura do aluno ficará salvo com o id
//   do contrato (pois pode ter mais de um contrato).

// 2) Na Aba Documento do cadastro de Cliente, dividir em 2 sub-abas. a que ja existe, 
// permanecerá e sera criada uma outra chamada Contrato Digital, onde ficarão os contratos 
// digitais com assinaturas e os termos de adesão com os aceites.

// 1) Assinatura Digital (tablet ou cel)
// NOTA: Como deve funcionar:
// Atraves de um tablet ou celular, o aluno tera acesso ao contrato digital onde, ao final,
//  terá um espaço para ele assinar (com uma caneta especial ou o dedo mesmo). ao clicar em gravar,
//   essa imagem ficará armazenada dentro do contrato, como uma assinatura mesmo e esse documento virará 
//   um pdf que ficara armazenado dentro da aba Documentos (cadastro de cliente). Uma copia do 
//   contrato devera ser mandado para o aluno, nao necessariamente de forma automatica.

// 2) Aceite da Adesão (tablet ou cel)
// NOTA: Como deve funcionar:
// O aceite deve ser enviado para o cliente, via email (link) e o mesmo deve receber o texto enviado
//  p/ academia e ao final ter um botão de 'aceito os termos'.
//   Feito isso, esse aceite deve retornar ao sistema e ficar registrado como um arquivo em pdf dentro da aba Documentos 
//   (cadastro de cliente), onde ao final do texto da Adesao, ficará registrado local, data, hora e a maquina onde foi feito o aceite.

// 3) Armazenamento do contrato digital com o aceite (data do aceite) e a assinatura do aluno na aba Documento
// 4) Validacao/exibição do contrato por:
// 4.1) Loja,
// 4.2) Grupo/Atividade
// 4.3) Plano.
// 5) Incluir nova rotina de gravação de log de INCLUSÃO e ALTERAÇÃO de contratos.
// 6) Incluir campo variavel (usuario que lancou o plano)


var canvas = document.getElementById('signature-pad');
const url = 'upload/upload.php';

const dominio = window.location.host;
   
// if (dominio.indexOf("127.0.0.1") > -1) {
//     var url_atual = 'http://127.0.0.1/projetos/mu.fitness.gestao/'
// } else if (dominio.indexOf("teste") > -1) {
//     var url_atual = 'https://teste.mufitness.com.br/v2/'
// } else {
//     var url_atual = 'https://app.mufitness.com.br/v2/'
// }

const  contrato = 179;
const cliente_id = '';
const urlAssinaturaContrato = 'Pessoas/assinatura.jpg';

var quadroBranco = new SignaturePad(canvas, {
    backgroundColor: 'rgb(255, 255, 255)' 
});

var specialElementHandlers = {
    "#hidden-element": function (element, renderer) {
        return true;
    }
};



    document.getElementById('assinar').addEventListener('click', function () {
        $('.modalAssinatura').modal('show')          
        
     });


     document.getElementById('clear').addEventListener('click', function () {
        quadroBranco.clear();
    });
    
    
    
    document.getElementById('draw').addEventListener('click', function () {
        var ctx = canvas.getContext('2d');
        ctx.globalCompositeOperation = 'source-over'; 
    });
     


$(document).on("click", '#save-png', function () {   
    $('.assinaturaCliente').append(`<img src=${urlAssinaturaContrato} width='20' height='20'>`) 
    var id_cliente = $('#id_cliente').val()
    var id_contrato = $('#id_contrato').val()
    $('.assinaturaEmpresa').remove()
    var assinaturaCliente = quadroBranco.toDataURL('image/png');

   
    if (quadroBranco.isEmpty()) {
        return alert("Por favor a assinatura é obrigátoria");
    }

    /**/
    let params = new URLSearchParams();
        params.append('image', assinaturaCliente);
        params.append('contato_html', $('#contrato').html());        

        converterUrlDeImageParaBase64(urlAssinaturaContrato,   
            function(dataUrl) {
            var doc = new jsPDF({
                orientation: 'p',
                format: 'a4',
            });
                doc.setFont("courier");
                doc.setFontType("normal");
                doc.setFontSize(24);
                doc.setTextColor(100);
                finalTexto = $('.conteudo_5').text().indexOf('\n\nContratante:\n\nMatricula:')
                ultimo_texto = $('.conteudo_5').text().substr(0,finalTexto )
                $('.conteudo_5').text(ultimo_texto)
                $('.assinaturaCliente').remove()   
                $('.conteudo_5').remove();
                
            doc.fromHTML($("#contrato").html(), 10, 10, {
                'width': 170,   
                'margin-top':90,      
                'elementHandlers': specialElementHandlers
            },function(bla){
                
                
                var arquivoPdfContrato = doc.output('blob');       
                var formData = new FormData();
                formData.append('pdf', arquivoPdfContrato);
                formData.append('uploadPDF', true);
                formData.append('id_cliente', id_cliente);
                formData.append('id_contrato', id_contrato);
                formData.append('contato_html', $('#contrato').html());
                let nome_cliente = $('#nome_cliente').val()
                cadastrarAssinatura(params) 
                $.ajax(url,
                {
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data){
                    
                    },
                    error: function(data){
                    
                    }
                });
                
                doc.addImage(dataUrl, 'PNG', 5, 125, 50, 50) 
                doc.addImage(assinaturaCliente, 'PNG', 120, 130, 50, 50)
                doc.text('___________',120,170).setFontSize(12).setFont('Arial','Bold')
                doc.text('Contratante',130,177).setFontSize(12).setFont('Arial','Bold')
                doc.text('Contratado',30,177).setFontSize(12).setFont('Arial','Bold')
                doc.text(nome_cliente,30,200).setFontSize(12).setFont('Arial','Bold')
                //doc.addImage(assinaturaPatrao, 'PNG', 120, 150, 50, 50)             
                doc.save('contrato.pdf');
                
        });
  
        //doc.addImage(data, 'PNG', lado,  cima, 50, 50) 
        // Quanto maior o  numero cima + desce 
        

        })
    });




function getContrato(id_contrato) { 
    $('#contrato').html(' ')
    $.ajax({
         url : "api/api.php?getContrato=true&id_contrato="+id_contrato,
         type : 'GET',        
         beforeSend : function(){
              $("#resultado").html("ENVIANDO...");
             }
           })
         .done(function(data){       
              $('#contrato').html(data)
            //  $('#contato_html').val(data)
             let linha_contratante = $('#contrato').html().indexOf('<p><strong>Contratante:</strong></p>')
             if(linha_contratante > 1){
                $('#contrato').html($('#contrato').html().substring(0, linha_contratante))
             }
     
            
         })
          .fail(function(jqXHR, textStatus, msg){
           alert(msg)
    });
}


function getContratoIdCliente() { 
    $('#contrato').html(' ')
    $.ajax({
         url : "api/api.php?getContratoByIdCliente=true&=",
         type : 'GET',        
         beforeSend : function(){
              $("#resultado").html("ENVIANDO...");
             }
           })
         .done(function(data) {       
            res = JSON.parse(data)
              $('.cliente_dados').text(res[0].razao_social) 
              $('#nome_cliente').val(res[0].razao_social)
         })
          .fail(function(jqXHR, textStatus, msg){
           alert(msg)
    });
}


    
const cadastrarAssinatura = (params) => {  
        axios({
            method: 'post',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            url: 'upload/upload.php',
            contentType: 'json',
            data: params
        }).then((response) => {
              
        
        }).finally((response) => {
           
        });
        
    }
     
    
   
    
    
function converterUrlDeImageParaBase64(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function() {
     var reader = new FileReader();
   
     reader.onloadend = function() {
            callback(reader.result);
        }
          reader.readAsDataURL(xhr.response);
        };
        xhr.open('GET', url);
        xhr.responseType = 'blob';
        xhr.send();
    }
      
     
      






