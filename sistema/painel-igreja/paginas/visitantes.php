<?php 
$pag = 'visitantes';

//verificar se ele tem a permissão de estar nessa página
if(@$clientes == 'ocultar'){
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

<!-- Modal Perfil -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form">
				<div class="modal-body">


					<div class="row">
						<div class="col-md-12 mb-2 col-6">							
							<label>Nome</label>
							<input type="text" class="form-control" id="nome" name="nome" placeholder="Seu Nome" required>							
						</div>
						</div>

						<div class="row">
						<div class="col-md-12 col-6">							
							<label>Telefone</label>
							<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Seu Telefone">							
						</div>
						</div>

						<div class="row">
						<div class="col-md-12">							
							<label>Email</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="Email" >							
						</div>
						</div>

						<div class="row">
						<div class="col-md-12 mb-2">							
							<label>Endereço</label>
							<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Seu Endereço" >							
						</div>
						</div>

						<div class="row">
						<div class="col-md-12">
							<label class="">Desejo</label>
							<input type="text" maxlength="255" class="form-control" id="desejo" name="desejo" placeholder="Receber Visita, Receber Oração, etc" >
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
					</div>
				</form>
			</div>
		</div>
	</div>







	<!-- Modal Dados -->
	<div class="modal fade" id="modalDados" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_dados"></span></h4>
					<button id="btn-fechar-dados" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
				</div>

				<div class="modal-body">


					<div class="row">


						<div class="col-md-12">
							<div class="tile">
								<div class="table-responsive">
									<table id="" class="text-left table table-bordered">

										<tr>
											<td class="bg-warning alert-warning">Email</td>
											<td><span id="email_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning">Telefone</td>
											<td><span id="telefone_dados"></span></td>
										</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Endereço</td>
										<td><span id="endereco_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Desejo</td>
										<td><span id="desejo_dados"></span></td>
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
	$("#form-baixar").submit(function () {
		event.preventDefault();
		var formData = new FormData(this);

		var id_conta = $('#id_contas').val(); 	

		$.ajax({
			url: 'paginas/receber/baixar.php',
			type: 'POST',
			data: formData,

			success: function (mensagem) {						

				$('#mensagem-baixar').text('');
				$('#mensagem-baixar').removeClass()
				if (mensagem.trim() == "Baixado com Sucesso") {                    
					$('#btn-fechar-baixar').click();
					listarDebitos(id_conta);

				} else {
					$('#mensagem-baixar').addClass('text-danger')
					$('#mensagem-baixar').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>
