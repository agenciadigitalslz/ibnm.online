

function inserir(){
	$('#mensagem').text('');
	$('#tituloModal').text('Inserir Registro');
    limpar();
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {
		backdrop: 'static',
	});
	myModal.show();
    
}


function excluir(id, nome){
    $('#id-excluir').val(id);
    $('#nome-excluido').text(nome);
    var myModal = new bootstrap.Modal(document.getElementById('modalExcluir'), {       });
    myModal.show();
    $('#mensagem-excluir').text('');
    limpar();
}




function mudarStatus(id, ativar){
      
    $.ajax({
        url: pag + "/mudar-status.php",
        method: 'POST',
        data: {id, ativar},
        dataType: "text",

        success: function (mensagem) {
            if (mensagem.trim() == "Alterado com Sucesso") {
                 window.location="index.php?pag=" + pag;
            }else {
                 mensagemErroAtivar()
                }               
        },

    });
}





$("#form").submit(function () {
	event.preventDefault();
	var formData = new FormData(this);

	$.ajax({
		url: pag + "/inserir.php",
		type: 'POST',
		data: formData,

		success: function (mensagem) {
			$('#mensagem').text('');
			$('#mensagem').removeClass()
			if (mensagem.trim() == "Salvo com Sucesso") {
                    //$('#nome').val('');
                    //$('#cpf').val('');
                     $('#btn-fechar').click();
                     mensagemSalvar();
                     
                     setTimeout(function(){
                        window.location="index.php?pag=" + pag;
                    }, 500)
                     
                    
                     
                } else {

                	$('#mensagem').addClass('text-danger')
                	$('#mensagem').text(mensagem)
                }


            },

            cache: false,
            contentType: false,
            processData: false,
            
        });

});




$("#form-excluir").submit(function () {
    event.preventDefault();
    var formData = new FormData(this);
    
    $.ajax({
        url: pag + "/excluir.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-excluir').text('');
            $('#mensagem-excluir').removeClass()
            if (mensagem.trim() == "Excluído com Sucesso") {
                $('#btn-fechar-excluir').click();
                mensagemExcluir()

                 setTimeout(function(){
                        window.location="index.php?pag=" + pag;
                    }, 500)
               		
            } else {

                $('#mensagem-excluir').addClass('text-danger')
                $('#mensagem-excluir').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});




$(document).ready(function() {
	$('#example').DataTable({
		"ordering": false
	});
} );





function carregarImg() {
    var target = document.getElementById('target');
    var file = document.querySelector("input[type=file]").files[0];
    var arquivo = file['name'];
    resultado = arquivo.split(".", 2);
        //console.log(resultado[1]);
        if(resultado[1] === 'pdf'){
            $('#target').attr('src', "../img/pdf.png");
            return;
        }

        if(resultado[1] === 'rar' || resultado[1] === 'zip'){
            $('#target').attr('src', "../img/rar.png");
            return;
        }

       if(resultado[1] === 'doc' || resultado[1] === 'docx'){
            $('#target').attr('src', "../img/word.png");
            return;
        }

        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }




function carregarImg2() {
    var target = document.getElementById('target-usu');
    var file = document.querySelector("#imagem-usu").files[0];
    var arquivo = file['name'];
    resultado = arquivo.split(".", 2);
        //console.log(resultado[1]);
        if(resultado[1] === 'pdf'){
            $('#target-usu').attr('src', "../img/pdf.png");
            return;
        }

        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }






$("#form-obs").submit(function () {
    event.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: pag + "/obs.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-obs').text('');
            $('#mensagem-obs').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
                    //$('#nome').val('');
                    //$('#cpf').val('');
                    $('#btn-fechar-obs').click();
                     window.location="index.php?pag=" + pag;
                } else {

                    $('#mensagem-obs').addClass('text-danger')
                    $('#mensagem-obs').text(mensagem)
                }


            },

            cache: false,
            contentType: false,
            processData: false,
            
        });

});



function mensagemSalvar(){
   
toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-bottom-center",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "1000",
  "hideDuration": "1000",
  "timeOut": "2000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

 Command: toastr["success"]("Registro Salvo com Sucesso!!", "Salvo")

}




function mensagemExcluir(){
 
toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-bottom-center",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "2000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

Command: toastr["info"]("Registro Excluído!!", "Excluído")

}



function mensagemErroAtivar(){
 
toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-bottom-center",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

Command: toastr["error"]("Não é possível ter mais de um alerta ativo ao mesmo tempo.", "Erro ao Ativar")


}