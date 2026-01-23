$(document).ready(function() { 
    $('#listar').text("Carregando Dados...");   
    $('#btn_carregando').hide();

    if (typeof buscar === 'function') {
        buscar();
    } else {
        listar();
    }
} );

function listar(p1, p2, p3, p4, p5, p6){
    // p1=filtro, p2=dataInicial, p3=dataFinal, p4=tipo_data, p5=usuario_lanc (opcional)
    var payload = { p1: p1, p2: p2, p3: p3, p4: p4 };
    if (typeof p5 !== 'undefined' && p5 !== null && p5 !== '') {
        payload.usuario_lanc = p5;
    }
    if (typeof p6 !== 'undefined') {
        payload.p6 = p6;
    }

    $.ajax({
        url: 'paginas/' + pag + "/listar.php",
        method: 'POST',
        data: payload,
        dataType: "html",
        success:function(result){
            // Evita reinitialise ao recarregar o conteúdo da lista
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tabela')) {
                $('#tabela').DataTable().destroy();
            }
            $("#listar").html(result);
            // Executa scripts embutidos no HTML retornado (atualiza cards/DT)
            $("#listar").find("script").each(function(){
                $.globalEval(this.text || this.textContent || this.innerHTML || "");
            });
            $('#mensagem-excluir').text('');
        }
    });
}

function reloadWithCurrentFilters(){
    var hasDates = $('#dataInicial').length && $('#dataFinal').length;
    if (hasDates) {
        var p1 = $('#tipo_data_filtro').val();
        var p2 = $('#dataInicial').val();
        var p3 = $('#dataFinal').val();
        var p4 = $('#tipo_data').val();
        var p5 = $('#filtro_usuario_lanc').length ? $('#filtro_usuario_lanc').val() : undefined;
        listar(p1, p2, p3, p4, p5);
        return;
    }
    if (typeof buscar === 'function') {
        buscar();
    } else {
        listar();
    }
}

function inserir(){    
    $('#mensagem').text('');
    $('#titulo_inserir').text('Inserir Registro');
    $('#modalForm').modal('show');
    limparCampos();
}





$("#form").submit(function () {

    event.preventDefault();
    var formData = new FormData(this);

    $('#mensagem').text('Salvando...')
    $('#btn_salvar').hide();

    $.ajax({
        url: 'paginas/' + pag + "/salvar.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem').text('');
            $('#mensagem').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {

                $('#btn-fechar').click();
                reloadWithCurrentFilters();

                $('#mensagem').text('')          

            } else {

                $('#mensagem').addClass('text-danger')
                $('#mensagem').text(mensagem)
            }

            $('#btn_salvar').show();

        },

        cache: false,
        contentType: false,
        processData: false,

    });

});




function excluir(id){   
    $('#mensagem-excluir').text('Excluindo...')
    
    $.ajax({
        url: 'paginas/' + pag + "/excluir.php",
        method: 'POST',
        data: {id},
        dataType: "html",

        success:function(mensagem){
            alert(mensagem)
            if (mensagem.trim() == "Excluído com Sucesso") {                
                reloadWithCurrentFilters();
            } else {
                $('#mensagem-excluir').addClass('text-danger')
                $('#mensagem-excluir').text(mensagem)
            }
        }
    });
}




function excluirMultiplos(id){   
    $('#mensagem-excluir').text('Excluindo...')
    
    $.ajax({
        url: 'paginas/' + pag + "/excluir.php",
        method: 'POST',
        data: {id},
        dataType: "html",

        success:function(mensagem){
            if (mensagem.trim() == "Excluído com Sucesso") {                
                reloadWithCurrentFilters();
            } else {
                $('#mensagem-excluir').addClass('text-danger')
                $('#mensagem-excluir').text(mensagem)
            }
        }
    });
}



function ativar(id, acao){  
    $.ajax({
        url: 'paginas/' + pag + "/mudar-status.php",
        method: 'POST',
        data: {id, acao},
        dataType: "html",

        success:function(mensagem){
            if (mensagem.trim() == "Alterado com Sucesso") {
                reloadWithCurrentFilters();
            } else {
                $('#mensagem-excluir').addClass('text-danger')
                $('#mensagem-excluir').text(mensagem)
            }
        }
    });
}