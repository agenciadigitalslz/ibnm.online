<?php 
$pag = 'aniversariantes';

//verificar se ele tem a permissão de estar nessa página
if(@$clientes == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
	exit();
}

?>


<div  style="display: inline-block; font-size: 14px; padding:5px; margin-top: 20px!important;">
			<span class="ocultar_mobile" style="">
				<button class="btn btn-primary btn-sm" href="#" onclick="buscar('mes')">Aniversáriantes do Mês</button> / 
				<button class="btn btn-success btn-sm" href="#" onclick="buscar('dia')">Aniversáriantes do Dia</button>
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



<!-- Modal Arquivos -->
<div class="modal fade" id="modalArquivos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="tituloModal">Gestão de Arquivos - <span id="nome-arquivo"> </span></h4>
				<button id="btn-fechar-arquivos" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form-arquivos" method="post">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-8">						
							<div class="form-group"> 
								<label>Arquivo</label> 
								<input class="form-control" type="file" name="arquivo_conta" onChange="carregarImgArquivos();" id="arquivo_conta">
							</div>	
						</div>
						<div class="col-md-4">	
							<div id="divImgArquivos">
								<img src="images/arquivos/sem-foto.png"  width="60px" id="target-arquivos">									
							</div>					
						</div>




					</div>

					<div class="row" >
						<div class="col-md-8">
							<input type="text" class="form-control" name="nome-arq"  id="nome-arq" placeholder="Nome do Arquivo * " required>
						</div>

						<div class="col-md-4">										 
							<button type="submit" class="btn btn-primary">Inserir</button>
						</div>
					</div>

					<hr>

					<small><div id="listar-arquivos"></div></small>

					<br>
					<small><div align="center" id="mensagem-arquivo"></div></small>

					<input type="hidden" class="form-control" name="id-arquivo"  id="id-arquivo">


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
											<td class="bg-warning alert-warning">CPF</td>
											<td><span id="cpf_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning">Email</td>
											<td><span id="email_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Telefone</td>
											<td><span id="telefone_dados"></span></td>
										</tr>

									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Endereço</td>
										<td><span id="endereco_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Data de Cadastro</td>
										<td><span id="data_cad_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Data de Nascimento</td>
										<td><span id="data_nasc_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Igreja</td>
										<td><span id="igreja_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Data de Batismo</td>
										<td><span id="data_bat_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Cargo</td>
										<td><span id="cargo_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Estado Cívil</td>
										<td><span id="estado_dados"></span></td>
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
	$("#form-arquivos").submit(function () {
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/arquivos.php",
			type: 'POST',
			data: formData,

			success: function (mensagem) {
				$('#mensagem-arquivo').text('');
				$('#mensagem-arquivo').removeClass()
				if (mensagem.trim() == "Inserido com Sucesso") {                    
						//$('#btn-fechar-arquivos').click();
					$('#nome-arq').val('');
					$('#arquivo_conta').val('');
					$('#target-arquivos').attr('src','images/arquivos/sem-foto.png');
					listarArquivos();
				} else {
					$('#mensagem-arquivo').addClass('text-danger')
					$('#mensagem-arquivo').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>

<script type="text/javascript">
	function listarArquivos(){
		var id = $('#id-arquivo').val();	
		$.ajax({
			url: 'paginas/' + pag + "/listar-arquivos.php",
			method: 'POST',
			data: {id},
			dataType: "text",

			success:function(result){
				$("#listar-arquivos").html(result);
			}
		});
	}

</script>




<script type="text/javascript">
	function carregarImgArquivos() {
		var target = document.getElementById('target-arquivos');
		var file = document.querySelector("#arquivo_conta").files[0];

		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);

		if(resultado[1] === 'pdf'){
			$('#target-arquivos').attr('src', "images/pdf.png");
			return;
		}

		if(resultado[1] === 'rar' || resultado[1] === 'zip'){
			$('#target-arquivos').attr('src', "images/rar.png");
			return;
		}

		if(resultado[1] === 'doc' || resultado[1] === 'docx' || resultado[1] === 'txt'){
			$('#target-arquivos').attr('src', "images/word.png");
			return;
		}


		if(resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls'){
			$('#target-arquivos').attr('src', "images/excel.png");
			return;
		}


		if(resultado[1] === 'xml'){
			$('#target-arquivos').attr('src', "images/xml.png");
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


<script type="text/javascript">

		function buscar(tipo){	

			listar(tipo);
		}

		</script>