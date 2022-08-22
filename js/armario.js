$(document).ready(function(){


    const botao_cadastrar = document.querySelector('#botao_cadastrar');
    const botao_imprimir = document.querySelector('#imprimir_armario');
    const botao_editar = '#editar_armario'
    const botao_editar_vinculo = '#editar_vinculo'
    const botao_excluir = 'excluir_armario'
    const mensagem_confirm_excluir = "Deseja excluir o armário ?";
    const url_api_armario = 'armario/api/apiArmario.php?';
    const buscar_armario = '#bt_buscar_armario'
    const input_buscar_armario = '#text_buscar_armario'
    const tabela_do_grid = $('#listar_armario')
    const tabela_do_grid_vinculos = $('#historico_armario_row');
    const search = $('#search').val()
    const id_order = '.id_order'
    const genero_order = '.genero_order'
    const tamanho_order = '.tamanho_order'
    const valor_order = '.valor_order'
    const filtro_status = '#filtro_status'
    
    $('#botoes_renovar_cadastrar_arm').hide();
    
    
    var order = 1;
    
    
    const modalArmario = (data, acao) => {
        $('#modal_armario').modal('show');
      
        if (acao == 'cadastrar') {
            $('.modal-titulo-aviso').text('Cadastrar Armário');
            $('.btn-salvar-modal-micro').text('Cadastrar');
            $('.btn-salvar-modal-micro').removeClass('Salvar');
            $('.btn-salvar-modal-micro').addClass('Cadastrar');
            $('.modal-micro-body').html(formCadastrar())
            return;
        }
    
        if (acao == 'editar') {
            $('.modal-titulo-aviso').text('Editar Armário');
            $('.btn-salvar-modal-micro').text('Salvar');
            $('.btn-salvar-modal-micro').removeClass('Cadastrar');
            $('.btn-salvar-modal-micro').addClass('Salvar');
            $('.modal-micro-body').html(formEditar(data))
            return;
        }
    
        if (acao == 'editar_vinculo') {
            $('.modal-titulo-aviso').text('Editar Armário Vinculo');
            $('.btn-salvar-modal-micro').text('Salvar');
            $('.btn-salvar-modal-micro').removeClass('Cadastrar');
            $('.btn-salvar-modal-micro').removeClass('Salvar');
            $('.btn-salvar-modal-micro').addClass('salvar_vinculo');
            $('.modal-micro-body').html(formEditarVinculo(data))
            return;
        }
    }
    
    const ordenacao = (items) => {
        items.sort(function (a, b) {
            return a.id - b.id;
        });
    }
    
    
    const getAll = (orderBy = null, field = null) => {
        order = orderBy == 2 ? 0 : orderBy
        tabela_do_grid.empty()
        fetch(url_api_armario + 'getAll=true&orderBy=' + order + '&fieldOrder=' + field)
            .then(response => response.text())
            .then(res => {
                results(res)
            })
    }
    
    
    const getAllArmariosVinculo = (orderBy = null, field = null) => {
        order = orderBy == 2 ? 0 : orderBy
        $('#armarios_vinculos_select').empty()
        fetch(url_api_armario + 'getAll=true&orderBy=' + order + '&fieldOrder=' + field)
            .then(response => response.text())
            .then(res => {
                data = JSON.parse(res)
                
                $.each(data, function (i, tamanho) {
                    
    
                    $("#armarios_vinculos_select").append($('<option></option>', {value: data[i].id, text: data[i].nomeArmario}));
                });
            
            })
    }
    
    const resultsVinculos = (data) => {
        let res = JSON.parse(data)
        if (res.length > 0) {
            for (x = 0; x < res.length; x++) {
                rowVinculos(res[x])
            }
            return;
        }
        tabela_do_grid_vinculos.text('Não tem resultado')
    }
    
    
    const getAllVinculosArmarios = (orderBy = null, field = null) => {
        order = orderBy == 2 ? 0 : orderBy
        tabela_do_grid_vinculos.empty()
        fetch(url_api_armario + 'getAllForncedorDespesasArmarios=true&orderBy=' + order + '&fieldOrder=' + field)
            .then(response => response.text())
            .then(res => { 
                try {
                    resultsVinculos(res)    
                } catch (error) {
                    
                }   
            
            })
    }
    
    
    
    $(document).on("click", '#historico_armario_menu', function () {
        $('#historico_armario_menu').css('background','#f8f8f8')
        $('#cadastro_armario_menu').css('background','#fff')
    
      
        $('#historico_armario').show();
        $('#cadastro_armario').hide();
        $('#text_buscar_armario').removeClass('buscar_armario');
        $('#text_buscar_armario').addClass('buscar_historico');
        $('#bt_buscar_armario').addClass('bt_buscar_armario');
        $('#bt_buscar_armario').removeClass('bt_buscar_historico');
    
        getAllVinculosArmarios()
    })
    
    $(document).on("click", '#cadastro_armario_menu', function () {
        $('#historico_armario_menu').css('background','#fff')
        $('#cadastro_armario_menu').css('background','#f8f8f8')
        $('#historico_armario').hide();
        $('#cadastro_armario').show();
        $('#text_buscar_armario').removeClass('buscar_historico');
        $('#text_buscar_armario').addClass('buscar_armario');
        $('#bt_buscar_armario').removeClass('bt_buscar_armario');
        $('#bt_buscar_armario').addClass('bt_buscar_historico');
        getAll();
    })
    
    const getServicos = () => {
        var servicos = $('#servicos');
        fetch(url_api_armario + 'getServicos=true')
            .then(response => response.text())
            .then(data => {
                try {
                    let res = JSON.parse(data)            
                    for (var i = 0; i < res.length; i++) {
                        servicos.append('<option id=' + res[i].id + ' value=' + res[i].id + '>' + res[i].descricao + '</option>');
                    }                
                } catch (error) {
                    
                }         
            })
    }
    
    const getById = (value) => {
        tabela_do_grid.html(' ')
        fetch(url_api_armario + 'getById=true&id='+value+'&nome='+value)
            .then(response => response.text())
            .then(html => {
                results(html)
            })
        }
    
    
    const getByIdClient = () => {
         fetch(url_api_armario + 'getById=true&id=' +$('#matriculaAluno').val() )
            .then(response => response.text())
            .then(html => {
                try {
                    let res = JSON.parse(html)          
                    $('#armario').val(res[0].nome)
                    $('#armario_id').val(res[0].armario_id)
                    $('#id_venda').val(res[0].id_venda)
                    $('#armario_antigo_id').val(res[0].armario_id)
                    
                } catch (error) {
                    
                }
             
            })
    }
    const getByStatus = (status) => {
        tabela_do_grid.html(' ')
        fetch(url_api_armario + 'getAllByStatus=true&status=' + status)
            .then(response => response.text())
            .then(html => {
                results(html)
            })
    }
    
    const getByName = (name) => {
        tabela_do_grid.html(' ')
        fetch(url_api_armario + 'getById&nome=' + name)
            .then(response => response.text())
            .then(html => {
                results(html)
            })
    }
    
    
    const destroy = (id) => {
        bootbox.confirm(mensagem_confirm_excluir, function (result) {
            if (result === true) {
                fetch(url_api_armario + 'destroy=true&id=' + id)
                    .then(response => response.text())
                    .then(response => {
                        getAll()
                    })
    
            }
        });
    
    }
    
    const update = (params) => {
        $('.Salvar').prop('disabled', true)
        axios({
            method: 'post',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            url: url_api_armario,
            contentType: 'json',
            data: params
        }).then((response) => {
    
        }).finally((response) => {
            bootbox.alert("Atualizado com sucesso")
            setTimeout(() => {
                $('.modal').modal('hide')
                getAll()
            }, 2000);
        });
       
    
    }
    
    const updateVinculo = (params) => {
       
        axios({
            method: 'post',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            url: url_api_armario,
            contentType: 'json',
            data: params
        }).then((response) => {
    
        }).finally((response) => {
            bootbox.alert("Atualizado com sucesso")
            setTimeout(() => {
                $('#data_vencimento_armario').val( $('#data_validade').val())
                $('.modal').modal('hide')
                getAllVinculosArmarios()
                $('.renovar_armario_salvar').prop('disabled', false);
    
            }, 2000);
        });
    
    }
    
    
    const desvincularArmario = (params) => {   
        axios({
            method: 'post',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            url: url_api_armario,
            contentType: 'json',
            data: params
        }).then((response) => {
    
        }).finally((response) => {
            bootbox.alert("Atualizado com sucesso")
            setTimeout(() => {
                $('.modal').modal('hide')     
                $('#data_vencimento_armario').val()
                $('#armario').val()
               
    
            }, 2000);
        });
    
    }
    
    const updateStatusArmario = (params) => {
        axios({
            method: 'post',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            url: url_api_armario,
            contentType: 'json',
            data: params
        }).then((response) => {
    
        }).finally((response) => {     
            $('#armario_antigo_id').val($('#armario_id').val())
            $('#modal_armario_financeiro').modal('hide')
        });
    
    }
    
    const cadastroVinculo = (params) => {
        $('.bt_renovar_armario').attr('disabled', true);
        axios({
            method: 'post',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            url: url_api_armario,
            contentType: 'json',
            data: params
        }).then((response) => {
            bootbox.alert('Atualizado com successo')
    
        }).finally((response) => {
            $('#data_vencimento_armario').attr('disabled', true);
            setTimeout(() => {         
                $('.modal').modal('hide')
                getAllVinculosArmarios()
                $('.btn-salvar-armario-financeiro').attr('disabled', false);
                $('.bt_renovar_armario').attr('disabled', false);
              
            }, 3000);
        });
    
    }
    
    const cadastrarRelacao = (params) => {
        axios({
            method: 'post',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            url: url_api_armario ,
            contentType: 'json',
            data: params
        }).then((response) => {     
            if(response.data== '1'){
                bootbox.alert('Vinculado com successo')
                return;
            }
            bootbox.alert('Não pode ser Vinculado')
        }).finally((response) => {     
            setTimeout(() => {
                $('.modal').modal('hide')
                getAll()
            }, 2000);
        });
    
    }
    const cadastrar = (params) => {
        $('.Cadastrar').prop('disabled', true)
        axios({
            method: 'post',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            url: url_api_armario + '&store=true',
            contentType: 'json',
            data: params
        }).then((response) => {
         
            if(response.data== '1'){
                bootbox.alert('Cadastrado com successo')
                return;
            }
            bootbox.alert('Não pode Ser Cadastrado')
        }).finally((response) => {     
        
            setTimeout(() => {
                $('.modal').modal('hide')
              
                getAll()
            }, 2000);
            $('.Cadastrar').prop('disabled', false)
        });
    
    }
    getAll();
    getServicos();
    
    
    $(document).on("change", filtro_status, function () {
        getByStatus($("#filtro_status option:selected").val())
    })
    
    if(botao_cadastrar){
    botao_cadastrar.addEventListener('click', () => {
        modalArmario(data = null, 'cadastrar')
    })
    }
    
    
    $(document).on("click", botao_editar, function () {
        $('.Salvar').prop('disabled', false)
        modalArmario($(this).data('data'), 'editar')
    })
    
    $(document).on("click", botao_editar_vinculo, function () {
        modalArmario($(this).data('data'), 'editar_vinculo')
        getAllArmariosVinculo()
    })
    
    
    $(document).on("click", id_order, function () {
        order++
        getAll(order, 'id')
    
    })
    
    
    
    
    $(document).on("change", "#servicos", function () {
        
        let params = new URLSearchParams();
        params.append('insertRelacao', true);
        params.append('id_servico', $('#servicos').val());
        params.append('matricula', $('#id_cliente').val());
    
     
       if($('#servicos').val() != ''){
        cadastrarRelacao(params)
       }
        
    })
    
    $(document).on("click", genero_order, function () {
        order++
        getAll(order, 'genero')
    
    })
    
    
    //  $(document).on("click", tamanho_order, function () {
    //     tabela_do_grid.empty()
    //     order++
    //      getAll(order,'tamanho')
    
    //  })
    
    $(document).on("click", valor_order, function () {
        order++
        getAll(order, 'valor')
    
    })
    
    $(document).on("click", tamanho_order, function () {
        order++
        getAll(order, 'tamanho')
    
    })
    
    
    $(document).on("click", '#' + botao_excluir, function () {
        destroy($(this).data('id'));
    })
    
    
    $(document).on("click", '.bt_buscar_armario', function () {
        let id = $('#text_buscar_armario').val()
        getById(id);
    })
    
    $(document).on("change", input_buscar_armario, function () {
        let id = $('#text_buscar_armario').val()
    
        if (id != '') {
            getById(id);
            return;
        }
        getAll();
    
    })
    
    $(document).on("click", '#imprimir_armario', function () { 
        let situacao = $('#filtro_status').val();
        window.open('armario/api/export.php?situacao='+situacao);
        e.preventDefault();
    })
    
    
    $(document).on("change", '.data_validade_novo', function () {
        let data_validade = $(this).val();
        const timeElapsed = Date.now();
        const today = new Date(timeElapsed);
        let hoje  = today.toISOString(); 
    
        if(hoje.split('T')[0] > data_validade) {
            bootbox.alert("Por favor, a data de validade não pode ser menor que a data atual")
            $(this).val(' ')
            
            return;
        }
       
    })
    
    $(document).on("change", '.data_validade_renovar', function () {
        let data_antiga = $('#data_vencimento_armario_antiga').val() == '' ? '' : $('#data_vencimento_armario_antiga').val() ;
    
        let data_validade = $(this).val();
        const timeElapsed = Date.now();
        const today = new Date(timeElapsed);
        let hoje  = today.toISOString(); 
        if(hoje.split('T')[0] > data_validade) {
            bootbox.alert("Por favor, a data de validade não pode ser menor que a data atual")
            $(this).val(data_antiga)
            
            return;
        }
    });
    
    
    const  isValidateEdit = () =>{
          let  i = 0;
        if ($('#tamanho').val() == '') {
            document.querySelector('#tamanho').style.border="1px solid red";
            $('.tamanho_error').text('Por favor o campo tamanho é obrigátorio ')
            let  i = 1;
            return false
        }
    
        if ($('#genero').val() == '') {      
            document.querySelector('#genero').style.border="1px solid red";
            $('.genero_error').text('Por favor o campo genero é obrigátorio ')
            let  i = 1;
            return false
        }
    
        if ($('.valor_edit').val() == '') {
            document.querySelector('.valor_edit').style.border="1px solid red";
            $('.valor_error').text('Por favor o campo Valor é obrigátorio ')
            let  i = 1;
            return false
         
        }
        
            if(isNaN(parseFloat($('.valor_edit').val())) && isFinite($('#valor').val())){
                $('.valor_error').text('Por favor o campo Valor é obrigátorio ')
                let i = 1;
                return false;
            }
      
    
          if(i == 1){
            return false;
          }else{
            return true;
          }      
       
     
    }
    
    
    const isValidarArm = () =>{
        let isValid = 0;
        if($('.ckArmarioFinanceiro'+$('#id_venda').val()).is(':checked', true) == false ){
            $('#ckArmarioFinanceiro').css('border','1px solid red')
            bootbox.alert("Por favor, Selecione o produto")
            isValid = 1;
        } 
     
        if ($('#armario_financeiro').val() == '') {
            $('#armario_financeiro').css('border','1px solid red')
            isValid = 1;
        }
    
        if($('#data_validade').val() == ''){
            $('#data_validade').css('border','1px solid red')
            isValid = 1;
        }
    
        if(isValid == 0){       
            $('#data_validade').css('border','0')
            $('#armario_financeiro').css('border','0')
            $('#ckArmarioFinanceiro').css('border','0')
            return true;
        }
      
        return false
        
    }
    
    
    
    
    const  isValidateCad = () =>{
        let  i = 0;
      if ($('#tamanho').val() == '') {
          document.querySelector('#tamanho').style.border="1px solid red";
          $('.tamanho_error').text('Por favor o campo tamanho é obrigátorio ')
          let  i = 1;
          return false
      }
    
      if ($('#genero').val() == '') {      
          document.querySelector('#genero').style.border="1px solid red";
          $('.genero_error').text('Por favor o campo genero é obrigátorio ')
          let  i = 1;
          return false
      }
    
      if ($('#valor').val() == '') {
          document.querySelector('#valor').style.border="1px solid red";
          $('.valor_error_c').text('Por favor o campo Valor é obrigátorio ')
          let  i = 1;
          return false
       
      }
        if(i == 1){
          return false;
        }else{
          return true;
        }
         
     
    
    }
    
    
    
    
    
    $(document).on("click", '.Salvar', function () {
        
        let params = new URLSearchParams();
        params.append('update', true);
        params.append('id', $('#id').val());
        params.append('genero', $('#genero').val());
        params.append('tamanho', $('#tamanho').val());
        params.append('apelido', $('#apelido').val());
        params.append('status', $('#status').val());
        if(isValidateEdit()){
            update(params)
        }
       
    })
    
    $(document).on("click", '.salvar_vinculo', function () {  
        let params = new URLSearchParams();
        params.append('insertVinculo', true);
        params.append('id_vinculo', $('#id_vinculo').val());
        params.append('id_cliente', $('#id_matricula_vinculo').val());
        params.append('data_vencimento_vinculo', $('#data_vencimento_vinculo').val());
        params.append('armario_vinculo_id', $('#armarios_vinculos_select').val());
        params.append('id_venda', $('#id_venda').val());
        cadastroVinculo(params)
    
        //updateVinculo(params)
       
       
    })
    
    
    $(document).on("click", '.Cadastrar', function () {
        let params = new URLSearchParams();
        params.append('store', true);
        params.append('id', $('#id').val());
        params.append('genero', $('#genero').val());
        params.append('tamanho', $('#tamanho').val());
        params.append('apelido', $('#apelido').val());
        if(isValidateCad()){
            cadastrar(params)
        }
    
      
    })
    
    
    var formtGenero =(genero) =>{
        switch (genero) {
            case 'FEMININO':
               return 'F'
            break;
    
            case 'MASCULINO': 
            return 'M'           
                break;
    
            case 'UNISEX':   
            return  'U'         
                break;
        }
    
    }
    
    var row = (data) => {
        let nomeAluno = data.nome_aluno == null ? '' : data.nome_aluno ; 
        let row = `<tr>
        <td align="center" style='text-transform: uppercase; vertical-align:middle !important'>
        <center>
            <span title="${data.status == 0 ? 'Disponível' : 'Indisponivel'} " class="${data.status == 0 ? 'label-Ativo' : 'label-Inativo'}"
            style="display: table-cell; margin-left:30%;;
            width:10px; height:10px; border-radius:50%!important;"></span>
        </center>
    
        </td>
        <td style='text-transform: uppercase;' >${data.id}</td>
        <td style='text-transform: uppercase;'>${data.id+''+data.genero.substr(0, 1)+''+data.tamanho.substr(0, 1)}</td>
        <td style='text-transform: uppercase;'>${data.apelido}</td>
        <td style='text-transform: uppercase;'>${data.genero}</td>
        <td style='text-transform: uppercase;'>${data.tamanho}</td>
        
    
       
        <td style='text-align:center;'>
            <button class='btn btn-info'
            data-data ='${JSON.stringify(data)}'   
            data-id='${data.id}' 
            data-genero='${data.genero}'
            data-tamanho='${data.tamanho}' 
            data-apelido='${data.apelido}'  
            id="editar_armario">Editar</button>
            <button  id="${botao_excluir}" class='btn btn-danger' data-id='${data.id}'>excluir</button>
        </td>
     
    </tr>`;
        tabela_do_grid.append(row)
    }
    
    var rowVinculos = (data) => {   
        let row = `<tr>   
        <td style='text-transform: uppercase;' ><center>${data.id}</center></td>
        <td style='text-transform: uppercase;'>${data.id_fornecedor_despesa}</td>
        <td style='text-transform: uppercase;'><a target="_blank" href='../Academia/ClientesForm.php?id=${data.id_fornecedor_despesa}'>${data.nome_usuario}</a></td>
        <td style='text-transform: uppercase;'>${data.data_cad}</td>
        <td style='text-transform: uppercase;'>${data.armario_id}</td>
        <td style='text-transform: uppercase;'>${data.id_venda}</td>
        <td style='text-transform: uppercase;'>${data.validade}</td>
        <td style='text-align:center;'>
            <button class='btn btn-info'
            data-data ='${JSON.stringify(data)}'   
            data-id='${data.id}' 
            data-genero='${data.genero}'
            data-apelido='${data.apelido}'
            data-tamanho='${data.tamanho}' 
            data-valor='${parseFloat(data.valor).toFixed(2)}'  
            id="editar_vinculo">Editar</button>   
        </td>
     
    </tr>`;
        tabela_do_grid_vinculos.append(row)
    }
    
    
    
    var formEditar = (data) => {  
        return `
        <div id="formEditar">
        <div class="form-group">
            <div class="input">
                <label for="">Tamanho</label>
                <select id="tamanho">
                    <option  value='Padrao'  ${data.tamanho == 'Padrão' ? 'selected' : ''}>Padrão</option>
                    <option  value='Pequeno'  ${data.tamanho == 'Pequeno' ? 'selected' : ''}>Pequeno</option>
                    <option  value='Grande' ${data.tamanho == 'Grande' ? 'selected' : ''}>Grande</option>
                    <option  value='Medio' ${data.tamanho == 'Médio' ? 'selected' : ''}>Medio</option>
                </select>
                <small class='tamanho_error' style='color:red;'></small>
                <input type="hidden" class="form-control" id="id" value="${data.id}">
    
            </div>
        </div>
    
    <div class="form-group my-2" style="margin-top:20px;">
        <div class="input">
            <label for="" style="margin-bottom:2px;">Gênero</label>
            <select id="genero">
                <option  value='Padrao'   ${data.tamanho == 'Padrao' ? 'selected' : ''}>Padrão</option>
                <option  value='Feminino'  ${data.genero == 'Feminino' ? 'selected' : ''}>Feminino</option>
                <option  value='Masculino' ${data.genero == 'Masculino' ? 'selected' : ''}>Masculino</option>       
                <option  value='Unissex'   ${data.genero == 'Unissex' ? 'selected' : ''}>Unissex</option>
            </select>
            <small class='genero_error' style='color:red;'></small>
        </div>
    </div>
    
    <div class="form-group my-2" style="margin-top:20px;">
    <div class="input">
        <label for="">Status</label>
        <select id="status" disabled>
            <option  value='0'  ${data.status == '0' ? 'selected' : ''}>Liberado</option>
            <option  value='1'  ${data.status == '1' ? 'selected' : ''}>Indisponível</option>     
        </select>
        <small class='tamanho_error' style='color:red;'></small>
        <input type="hidden" class="form-control" id="id" value="${data.id}">
    </div>
    </div>
    
    <div class="form-group" style="margin-top:10px;">
    <div class="input">
        <label for="" style="margin-bottom:2px;">Nome Personalizado</label>
        <input type="text" class="form-control" id="apelido" value="${data.apelido}">
    </div>
    </div>
    
    </div>
    </div>`;
    
    }
    {/* <div class="form-group my-2" style="margin-top:20px;>
        <div class="input">
            <label for="" style="margin-bottom:2px;">Valor</label>
            <input type="number" class="form-control valor_edit" id="valor" value="${parseFloat(data.valor).toFixed(2)}">
            <small class='valor_error' style='color:red;'></small>
        </div>
    </div> */}
    
    var formEditarVinculo = (data) => { 
       
        return `
        <div id="formEditar">
    
    <div class="form-group my-2" style="margin-top:20px;">
    <div class="input">
        <label for="" style='margin-bottom:10px;'>Data de Vencimento</label>
        <input type="date" class="form-control data_vencimento_vinculo" id="data_vencimento_vinculo" value="${data.validade_us}" style='width:50%;'>
        <small class='tamanho_error' style='color:red;'></small>
        <input type="hidden" class="form-control" id="id_vinculo" value="${data.id}">
        <input type="hidden" class="form-control" id="id_matricula_vinculo" value="${data.id_fornecedor_despesa}">
        <input type="hidden" class="form-control" id="armario_vinculo_id" value="${data.armario_id}">
        <input type="hidden" class="form-control" id="id_venda" value="${data.id_venda}">
    </div>
    </div>
            <div class="form-group my-2" style="margin-top:5px;>
                <div class="input">
                    <label for="">Armarios</label>
                        <select id="armarios_vinculos_select">
                           
                        </select>
                    <small class='tamanho_error' style='color:red;'></small>
                    <input type="hidden" class="form-control" id="id" value="${data.id}">
                </div>
            </div>
    
          
    </div>
    </div>
    </div>
    </div>`;
    
    }
    
    var formCadastrar = () => {
        return `<div class="form-group">
        <div class="input">
            <label for="" style="margin-bottom:2px;">Tamanho</label>     
            <select id="tamanho">    
            <option  value='Padrao'>Padrão</option>
            <option  value='Pequeno'>Pequeno</option>    
            <option  value='Medio' >Médio</option>
            <option  value='Grande' >Grande</option>
        </select>
            <small class='tamanho_error' style='color:red;'></small>       
        </div>
    </div>
    
    <div class="form-group" style="margin-top:10px;">
        <div class="input">
            <label for="" style="margin-bottom:2px;">Gênero</label>
        <select id="genero">
                <option value='Feminino'>Feminino</option>
                <option value='Masculino'>Masculino</option>          
                <option  value='Unissex'>Unissex</option>
        </select>
        </div>
    </div>
    
    <div class="form-group" style="margin-top:10px;">
        <div class="input">
            <label for="" style="margin-bottom:2px;">Nome Personalizado</label>
            <input type="text" class="form-control" id="apelido" value="">
        </div>
    </div>
    
    
    </div>`;
    
    }
    
    
    var camposExtras = () =>{
    
    }
    
    const results = (data) => {
        try {
            let res = JSON.parse(data)
            if (res.length > 0) {
                for (x = 0; x < res.length; x++) {
                    row(res[x])
                }
                return;
            }
            
        } catch (error) {
            
        }
    
        tabela_do_grid.text('Não tem resultado')
    }
    
    
    
    {/* <div class="form-group" style="margin-top:10px;">
        <div class="input">
            <label for="" style="margin-bottom:2px;">Valor</label>
            <input type="number" id="valor"   />
            <small class='valor_error_c' style='color:red;'></small>
        </div>
    </div> */}
    
    
    
    const getFinanceiro = () => {
        $('#listar_armario_financeiro').empty() 
       
        fetch(url_api_armario + 'getFinanceiroArmario=true&matricula_id='+$('#matricula').val())
            .then(response => response.text())
            .then(res => {  
                try {
                    if(res == 0){
                        $('.modal-body').html(`<h3 style='text-align:center; font-size:14px;'> Cliente não possui armarios </h3>`);
                        $('#btn-salvar-armario-financeiro').hide();
                        return;
                    }
                    let financeiro = JSON.parse(res)     
                    for(x=0; x < financeiro.length; x++){
                        rowFinanceiroArmario(financeiro[x]);
                     
                    }                
                } catch (error) {
                    
                }
               
            })
    }
    
    const getVendaById = () => {
        $('#listar_armario_financeiro').empty() 
        fetch(url_api_armario + 'getVendaIdByCliente=true&matricula_id='+$('#matricula').val())
            .then(response => response.text())
            .then(res => {
                try {
                    $('.venda_historico').val(financeiro[0].id_venda) 
                    let financeiro = JSON.parse(res)  
                } catch (error) {            
                }                  
            })
    }
    
    const getFinanceiroVinculado = () => {
        $('#listar_armario_financeiro').empty() 
        fetch(url_api_armario + 'getFinanceiroArmarioVinculado=true')
            .then(response => response.text())
            .then(res => {
                console.log(res)
                try {
                  
                    let financeiro = JSON.parse(res)     
                    for(x=0; x < financeiro.length; x++){
                        rowFinanceiroArmario(financeiro[x]);
                    }
                    
                } catch (error) {
                    
                }
              
            })
    }
    
    const getFinanceiroVinculadoById = (matricula) => {
        $('#listar_armario_financeiro').empty() 
        fetch(url_api_armario + 'getFinanceiroArmarioVinculadoById=true&matricula='+matricula)
            .then(response => response.text())
            .then(res => {          
                try {               
                        let financeiro = JSON.parse(res)     
                    for(x=0; x < financeiro.length; x++){
                        rowFinanceiroArmario(financeiro[x]);
                        
                    }
                    $('.ckArmarioFinanceiro'+matricula).prop('disabled', true)
                } catch (error) {
                    
                }
              
            })
    }
    
    const getArmariosFinanceiro = () => {
        $('#armario_financeiro').html(" ")
            getUserById(getCookie('cookie_user'));
            var servicos = $('#armario_financeiro');
     fetch(url_api_armario + 'getAll=true')
         .then(response => response.text())
         .then(data => {
            try {
                let res = JSON.parse(data)        
                servicos.append('<option value="" selected>Selecione</option>'); 
                for (var i = 0; i < res.length; i++) {
                    servicos.append('<option id=' + res[i].id + ' value=' + res[i].id + '>' + res[i].id+''+res[i].genero.substr(0, 1)+''+res[i].tamanho.substr(0, 1) + '</option>');
                }   
                
            } catch (error) {
                
            }
                
         })
    }
    
    function renovarArmario(){
        if($('#armario').val() == ''){      
            return;
        }
        $('#modal_armario_financeiro').modal('show'); 
        $('.modal-title').text('Renovar Armário');
        $('.fechar-modal-top').attr('src','../../img/fechar.png');
        $('#armario_financeiro').prop('disabled', true);
        $('.renovar_armario_salvar').prop('disabled', false);
        $('#btn-salvar-armario-financeiro').removeClass('btn-salvar-armario-financeiro');
        $('#btn-salvar-armario-financeiro').addClass('renovar_armario_salvar');
        $('#desvincular_armario').show()
       
        $('#data_validade').removeClass('data_validade_novo')
        $('#data_validade').addClass('data_validade_renovar')
        $('.table').hide()   
        getFinanceiroVinculadoById($('#matriculaAluno').val())
        getArmariosFinanceiroByIdCliente($('#matriculaAluno').val())
    }
    
    
    const getArmariosFinanceiroByIdCliente = (id) => { 
        var servicos = $('#armario_financeiro');   
     fetch(url_api_armario + 'getById=true&id='+id)
         .then(response => response.text())
         .then(data => {
             let res = JSON.parse(data)  
             
                for (var i = 0; i < res.length; i++) {
                    $('#armario_antigo_id').val(res[i].armario_id)
                    $('#data_validade').val(res[i].validade_js )
                    servicos.append('<option selected id=' + res[i].armario_id + ' value=' + res[i].armario_id + '>'+res[i].nome+'</option>');
                }        
         })
    }
    
    const formRenovar = () => {
    return `<div class="col-12" id="validade_arm_financeiro" style="margin-left: 5px; width: 500px;">                            
               <div>
                    <label for="data_validade">Validade até:</label>
                    <input type="date" id="data_validade" style="border-radius:0 ; height:18px;">
                </div>
            </div>`;
    }
    
    
    const rowFinanceiroArmario = (data) => {
        let situacaoArmario = data.situacao_armario == 'alugado' ? 'disabled' : ''   
           let row = `<tr>
                        <td align="center" style="text-transform: uppercase;">                  
                            <input type="checkbox"                                                                                              
                                    ${situacaoArmario}
                                    id="ckArmarioFinanceiro" 
                                    name="addFinanceiro" 
                                    data-obj='${JSON.stringify(data)}'
                                    data-id='${data.id}'
                                    class='ckArmarioFinanceiro${data.id}'                                
                                    >               
                        </td>
                        <td style="text-transform: uppercase;">${data.id}</td>
                        <td style="text-transform: uppercase;">${data.nomeProduto}</td>  
                        <td style="text-transform: uppercase;">${data.data_venda}</td>                 
                    </tr>`;
                   
                    $('#listar_armario_financeiro').append(row);
                  
    }
    
    
    $(document).on("click", '#adicionar_novo_armario', function () {  
        $('#modal_armario_financeiro').modal('show');
        $('.fechar-modal-top').attr('src','../../img/fechar.png');
        $('.modal-title').css('font-family','Exo, serif') 
        $('.modal-title').text('Adicionar Armário') 
        $('#btn-salvar-armario-financeiro').addClass('btn-salvar-armario-financeiro')
        $('#btn-salvar-armario-financeiro').removeClass('renovar_armario_salvar')
    
        $('#armario_financeiro').prop('disabled',false)
        $('#id_venda').prop('disabled',false)
        $('#armario_id').prop('disabled',false);
        $('#data_vencimento_armario').attr('disabled', true); 
        $('.btn-salvar-armario-financeiro').prop('disabled',false)
      
        $('.table').show()
        $('#data_validade').addClass('data_validade_novo')
        $('#data_validade').removeClass('data_validade_renovar')
        $('#desvincular_armario').hide()
        getFinanceiro();
        getArmariosFinanceiro()
        getVendaById()
     
        $('#data_vencimento_armario').attr('disabled', true);
    })
    
    
    
    $(document).on("click", '.btn-salvar-armario-financeiro', function () {
        let dados = $(this).attr('data')  
        $('#data_vencimento_armario').attr('disabled', false);
      
        let paramsArmAntigo = new URLSearchParams();
        paramsArmAntigo.append('updateStatusArmario', true);
        paramsArmAntigo.append('status', 0);
        paramsArmAntigo.append('id_armario', $('#armario_antigo_id').val());
    
    
        let paramsArmNovo = new URLSearchParams();
        paramsArmNovo.append('updateStatusArmario', true);
        paramsArmNovo.append('status', 1);
        paramsArmNovo.append('id_armario', $('#armario_financeiro').val());
    
    
        let paramsVinculos = new URLSearchParams();
        paramsVinculos.append('insertVinculo', true);
        paramsVinculos.append('data_vencimento_vinculo',$('#data_validade').val());
        paramsVinculos.append('id_cliente', $('#matriculaAluno').val());
        paramsVinculos.append('id_venda', $('#id_venda').val());
        paramsVinculos.append('armario_vinculo_id', $('#armario_financeiro').val());
    
    
       
         if(isValidarArm( $('#matriculaAluno').val())){
           $('.btn-salvar-armario-financeiro').attr('disabled', true);
           updateStatusArmario(paramsArmAntigo);
           updateStatusArmario(paramsArmNovo);
           cadastroVinculo(paramsVinculos);
    
            $('.modal').modal('hide')
            $('#armario').val($("#armario_financeiro option:selected").text())
            $('#armario_id').val($("#armario_financeiro option:selected").val())
            $('#data_vencimento_armario').val($('#data_validade').val())
            
         }
       
    })
    
    $(document).on("click", '#ckArmarioFinanceiro', function () {    
        $('.btn-salvar-armario-financeiro').attr('data', ' ');
        let obj = $(this).data('obj');  
        let id = $(this).data('id')     
        let dados = $('.ckArmarioFinanceiro'+id).data('obj');   
    
        $('#validade_arm_financeiro').show('slow')      
        $('#id_venda').val(dados.id)     
        
       
    })
    
    
    $(document).on("click", '#desvincular_armario', function () {
        let params = new URLSearchParams();
        params.append('desvincularArmario', true);
        params.append('matricula_id',$('#id_cliente').val());
        params.append('armario_id', $('#armario_financeiro').val());
        params.append('id_venda', $('#id_venda').val());
        $('#data_vencimento_armario').val("")
        $('#armario').val("")
        desvincularArmario(params)
    
        let paramsArmAntigo = new URLSearchParams();
        paramsArmAntigo.append('updateStatusArmario', true);
        paramsArmAntigo.append('status', 0);
        paramsArmAntigo.append('id_armario', $('#armario_financeiro').val());
        updateStatusArmario(paramsArmAntigo);
    })
    
    $(document).on("click", '#renovar_armario', function () {
        renovarArmario()
    })
    
    
    $(document).on("click", '.renovar_armario_salvar', function () { 
        let params = new URLSearchParams();
        params.append('updateVinculoArmario', true);
        params.append('data_vencimento_vinculo',$('#data_validade').val());
        params.append('armario_id', $('#armario_financeiro').val());
        params.append('matricula_id',$('#id_cliente').val());
        params.append('id_venda', $('#id_venda').val());
    
        if($('#data_validade').val() == ''){
            bootbox.alert("Por favor, a data de validade não pode ser menor que a data atual")
           return;
        }
        updateVinculo(params)
        
        
    
     
        
    })
    
    $(document).on("click", '.bt_renovar_armario', function () {
        renovarArmario()
    })
    
    getByIdClient()
    });
    