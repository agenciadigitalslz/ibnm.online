<?php 
$pag = 'patrimonios';

//verificar se ele tem a permissão de estar nessa página
if(@$itens == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}
 ?>

 <div class="justify-content-between">
 	<div class="left-content mt-2 mb-3">
 <a class="btn ripple btn-primary text-white" onclick="inserir()" type="button"><i class="fe fe-plus me-2"></i> Adicionar <?php echo ucfirst($pag); ?></a>




<div class="dropdown" style="display: inline-block;">                      
                        <a href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="btn btn-danger dropdown" id="btn-deletar" style="display:none"><i class="fe fe-trash-2"></i> Deletar</a>
                        <div  class="dropdown-menu tx-13">
                        <div style="width: 240px; padding:15px 5px 0 10px;" class="dropdown-item-text">
                        <p>Excluir Selecionados? <a href="#" onclick="deletarSel()"><span class="text-danger">Sim</span></a></p>
                        </div>
                        </div>
                        </div>



</div>


</div>




<div class="row row-sm">
<div class="col-lg-12">
<div class="card custom-card">
<div class="card-body" id="listar">

</div>
</div>
</div>
</div>


<input type="hidden" id="ids">

<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				 <button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form_itens">
			<div class="modal-body">
				

					<div class="row">

						<div class="col-md-3 mb-2 col-6">							
								<label>Código</label>
								<input type="text" class="form-control" id="codigo" name="codigo" placeholder="Código do Item" required>							
						</div>

						<div class="col-md-6 mb-2 col-6">							
								<label>Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>							
						</div>		

						<div class="col-md-3 mb-2 col-6">							
								<label>Data Cadastro</label>
								<input type="date" class="form-control" id="data_cad" name="data_cad" value="<?php echo date('Y-m-d') ?>" required>							
						</div>
			
						
					</div>	


					<div class="row">
						
						<div class="col-md-12 mb-2 col-6">							
								<label>Descrição Item</label>
								<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição" required>							
						</div>
					</div>


					<div class="row">
							<div class="col-md-4 mb-2 col-5">							
								<label>Valor R$</label>
								<input type="text" class="form-control" id="valor" name="valor" placeholder="R$ Valor" required>							
						</div>	


						<div class="col-md-4 mb-2 col-5">
									<label>Entrada (Compra / Doação)</label>
									<select class="form-select" name="entrada" id="entrada">  
										<option value="Compra">Compra</option>
										<option value="Doação">Doação</option>

									</select>
								</div>

								<div class="col-md-4 mb-2 col-6">							
								<label>Doado por</label>
								<input type="text" class="form-control" id="doador" name="doador" placeholder="Nome do Doador">							
						</div>
			
						
					</div>


					<div class="row">
								<div class="col-md-12 mb-3">
								<label for="exampleFormControlInput1" class="">Observações</label>
								<textarea class="form-control textarea" id="obs" name="obs" maxlength="3000"></textarea>
							</div>
						</div>


					<div class="row">
						<div class="col-md-8 mb-2 col-8" >						
								<div class="form-group"> 
									<label>Foto <small>Indicado(200x200)</small></label> 
									<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
								</div>						
							</div>
							<div class="col-md-4 mb-2 col-4">
								<img src="images/sem-foto.jpg"  width="80px" id="target">	
							</div>

					</div>
										


					<input type="hidden" class="form-control" id="id" name="id">
					<input type="hidden" id="igreja" name="igreja" value="<?php echo $id_igreja ?>">				

				<br>
				<small><div id="mensagem" align="center"></div></small>
			</div>
			<div class="modal-footer">       
				<button type="submit" id="btn_salvar" class="btn btn-primary">Salvar</button>
			</div>
			</form>
		</div>
	</div>
</div>







	<!-- Modal Dados -->
	<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_dados"></span></h4>
					<button id="btn-fechar-dados" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
				</div>

				<div class="modal-body">


					<div class="row">


						<div class="col-md-6">
							<div class="tile">
								<div class="table-responsive">
									<table id="" class="text-left table table-bordered">
										<tr>
											<td class="bg-warning alert-warning">Código:</td>
											<td><span id="codigo_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning">Igreja Possui Item:</td>
											<td><span id="igreja_item_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Valor:</td>
											<td><span id="valor_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Data Cadastro:</td>
											<td><span id="data_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Compra / Doação:</td>
											<td><span id="entrada_dados"></span></td>
										</tr>
										
										<tr>
											<td class="bg-warning alert-warning w_150">Observações:</td>
											<td><span id="obs_dados"></span></td>
										</tr>




										 

									</table>
								</div>
							</div>
						</div>



						<div class="col-md-6">
							<div class="tile">
								<div class="table-responsive">
									<table id="" class="text-left table table-bordered">
									
									<tr>
											<td align="center"><img src="" id="foto_dados" width="200px"></td>
										</tr>      
										
									</table>
								</div>
							</div>
						</div>

					</div>





				</div>

			</div>
		</div>
	</div>






<!-- Modal -->
		<div class="modal fade" id="modalDevolver" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Devolver Item</span> - <span id="nome-item"></span></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form id="form-devolver" method="post">
						<div class="modal-body">

							Deseja Realmente devolver este Item: <span id="nome-devolver"></span>?

							<small><div id="mensagem-devolver" align="center"></div></small>

							<input type="hidden" class="form-control" name="id-transferir"  id="id-devolver">
							<input type="hidden" class="form-control" name="igreja" id="id-ig" >


						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-devolver">Fechar</button>
							<button type="submit" class="btn btn-success">Devolver</button>
						</div>
					</form>
				</div>
			</div>
		</div>




		<!-- Modal -->
<div class="modal fade" id="modalTransferir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Transferir - <span id="nome-transferir"></span></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form-transferir" method="post">
				<div class="modal-body">
					
					<div class="mb-3">
						<label for="exampleFormControlInput1" class="form-label">Igreja</label>
						<select class="form-control sel2" id="igreja" name="igreja" style="width:100%;" required>
							<?php 
							$query = $pdo->query("SELECT * FROM igrejas order by matriz desc, nome asc");
							$res = $query->fetchAll(PDO::FETCH_ASSOC);
							$total_reg = count($res);
							if($total_reg > 0){

								for($i=0; $i < $total_reg; $i++){
									foreach ($res[$i] as $key => $value){} 

										$nome_reg = $res[$i]['nome'];
									$id_reg = $res[$i]['id'];

									if($id_reg != $id_igreja){
										?>
										<option value="<?php echo $id_reg ?>" class="<?php echo $classe_ig ?>"><?php echo $nome_reg ?></option>

									<?php }}} ?>
								</select>
							</div>


							<small><div id="mensagem-transferir" align="center"></div></small>

							<input type="hidden" class="form-control" name="id-transferir"  id="id-transferir">


						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-trasferir">Fechar</button>
							<button type="submit" class="btn btn-success">Transferir</button>
						</div>
					</form>
				</div>
			</div>
		</div>





		<!-- Modal -->
		<div class="modal fade" id="modalDevolver" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Devolver Item</span> - <span id="nome-item"></span></h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form id="form-devolver" method="post">
						<div class="modal-body">

							Deseja Realmente devolver este Item: <span id="nome-devolver"></span>?

							<small><div id="mensagem-devolver" align="center"></div></small>

							<input type="hidden" class="form-control" name="id-transferir"  id="id-devolver">
							<input type="hidden" class="form-control" name="igreja" id="id-ig" >


						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-devolver">Fechar</button>
							<button type="submit" class="btn btn-success">Devolver</button>
						</div>
					</form>
				</div>
			</div>
		</div>



<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>



<script type="text/javascript">
	function carregarImg() {
    var target = document.getElementById('target');
    var file = document.querySelector("#foto").files[0];
    
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
</script>


<script type="text/javascript">
	function buscarCat(id){
		$('#cat').val(id); 
		listar(id)
	}
</script>



<script type="text/javascript">
	

$("#form_itens").submit(function () {

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
                var id = $('#cat').val()                
				listar(id)

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


$("#form-devolver").submit(function () {

				event.preventDefault();
				var formData = new FormData(this);

				$.ajax({
					url: 'paginas/patrimonios/transferir.php',
	type: 'POST',
	data: formData,

					success: function (mensagem) {
						alert(mensagem)
						$('#mensagem-transferir').text('');
						$('#mensagem-transferir').removeClass()
						if (mensagem.trim() == "Alterado com Sucesso") {
                    //$('#nome').val('');
                    //$('#cpf').val('');
                    $('#btn-fechar-trasferir').click();
                    mensagemSalvar();
					listar(id);

                    setTimeout(function(){
                    	window.location="index.php?pag=" + pag;
                    }, 500)

                    

                } else {

                	$('#mensagem-transferir').addClass('text-danger')
                	$('#mensagem-transferir').text(mensagem)
                }


            },

            cache: false,
            contentType: false,
            processData: false,
            
        });

			});



			$("#form-transferir").submit(function () {

event.preventDefault();
var formData = new FormData(this);

$.ajax({
	url: 'paginas/patrimonios/transferir.php',
	type: 'POST',
	data: formData,

	success: function (mensagem) {
		alert(mensagem)
		$('#mensagem-transferir').text('');
		$('#mensagem-transferir').removeClass()
		if (mensagem.trim() == "Alterado com Sucesso") {
	//$('#nome').val('');
	//$('#cpf').val('');
	$('#btn-fechar-trasferir').click();
	//mensagemSalvar();
	//listar(id);


	

	
		window.location.reload();
	

	

} else {

	$('#mensagem-transferir').addClass('text-danger')
	$('#mensagem-transferir').text(mensagem)
}


},

cache: false,
contentType: false,
processData: false,

});

});



$("#form-devolver").submit(function () {

event.preventDefault();
var formData = new FormData(this);

$.ajax({
	url: 'paginas/patrimonios/transferir.php',
	type: 'POST',
	data: formData,

	success: function (mensagem) {
		$('#mensagem-transferir').text('');
		$('#mensagem-transferir').removeClass()
		if (mensagem.trim() == "Alterado com Sucesso") {
	//$('#nome').val('');
	//$('#cpf').val('');
	$('#btn-fechar-trasferir').click();
	mensagemSalvar();
	listar(id);

	setTimeout(function(){
		window.location="index.php?pag=" + pag;
	}, 500)

	

} else {

	$('#mensagem-transferir').addClass('text-danger')
	$('#mensagem-transferir').text(mensagem)
}


},

cache: false,
contentType: false,
processData: false,

});

});



</script>



<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
$('.sel2').select2({
//placeholder: 'Selecione um Cliente',
dropdownParent: $('#modalTransferir')
});
});



</script>