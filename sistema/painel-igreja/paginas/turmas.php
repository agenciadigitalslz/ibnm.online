<?php 
$pag = 'turmas';

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
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form">
				<div class="modal-body">


					<div class="row">
						<div class="col-md-4 mb-2 col-6">							
							<label>Nome</label>
							<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome da Turma" required>							
						</div>

						<div class="col-md-4 mb-2 col-6">							
							<label>Dias</label>
							<input type="text" class="form-control" id="dias" name="dias" placeholder="Ex. Toda Segunda" >							
						</div>

						<div class="col-md-4">							
							<label>Horário</label>
							<input type="text" class="form-control" id="hora" name="hora" placeholder="Das 18:00 as 20:00" >							
						</div>

						

					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="mb-3">
								<label>Data Início </label>
								<input type="date" class="form-control" id="data_inicio" name="data_inicio">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label>Data Término </label>
								<input type="date" class="form-control" id="data_termino" name="data_termino">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label>Status Turma</label>
								<select class="form-select" name="status" id="status">
									<option value="Não Iniciada">Não Iniciada</option>
									<option value="Andamento">Andamento</option>
									<option value="Finalizada">Finalizada</option>
								</select>
							</div>
						</div>
					</div>


				


						<div class="row">
						<div class="col-md-8">
							<div class="mb-3">
								<label >Local </label>
								<input type="text" class="form-control" id="local" name="local" placeholder="Rua, Número e Bairro">
							</div>
						</div>

						<div class="col-md-4">
							<div class="mb-3">
								<label >Pastor Responsável</label>
								<select class="form-control sel2" id="pastor" name="pastor" style="width:100%;">
									<option value="0">Selecione um Pastor</option>
									<?php 
									$query = $pdo->query("SELECT * FROM pastores where igreja = '$id_igreja' order by nome asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = count($res);
									if($total_reg > 0){

										for($i=0; $i < $total_reg; $i++){
											foreach ($res[$i] as $key => $value){} 

												$nome_reg = $res[$i]['nome'];
											$id_reg = $res[$i]['id'];
											?>
											<option value="<?php echo $id_reg ?>"><?php echo $nome_reg ?></option>

										<?php }} ?>
									</select>
								</div>
							</div>
						</div>
						
						
							
							<div class="row">

							<div class="col-md-4">
								
								<div class="mb-3">
									<label >Ministrador</label>
									<select class="form-control sel2" id="coordenador" name="coordenador" style="width:100%;">
										<option value="0"> Membro / Pastor</option>

										
										<?php 
										$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' and ativo = 'Sim' order by  nome asc");
										$res = $query->fetchAll(PDO::FETCH_ASSOC);
										$total_reg = count($res);
										if($total_reg > 0){

											for($i=0; $i < $total_reg; $i++){
												foreach ($res[$i] as $key => $value){} 

													$nome_reg = $res[$i]['nome'];
												$id_reg = $res[$i]['id'];
												?>
												<option value="<?php echo $id_reg ?>"><?php echo $nome_reg ?></option>

											<?php }} ?>
										</select>
									</div>
									
								</div>
								<div class="col-md-4">
									<div class="mb-3">
										<label >Ministrador 2</label>
										<select class="form-control sel2" id="lider1" name="lider1" style="width:100%;">
											<option value="0">Selecione um Membro</option>
											<?php 
											$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' and ativo = 'Sim' order by nome asc");
											$res = $query->fetchAll(PDO::FETCH_ASSOC);
											$total_reg = count($res);
											if($total_reg > 0){

												for($i=0; $i < $total_reg; $i++){
													foreach ($res[$i] as $key => $value){} 

														$nome_reg = $res[$i]['nome'];
													$id_reg = $res[$i]['id'];
													?>
													<option value="<?php echo $id_reg ?>"><?php echo $nome_reg ?></option>

												<?php }} ?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="mb-3">
											<label >Ministrador 3</label>
											<select class="form-control sel2" id="lider2" name="lider2" style="width:100%;">
												<option value="0">Selecione um Membro</option>
												<?php 
												$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' and ativo = 'Sim' order by nome asc");
												$res = $query->fetchAll(PDO::FETCH_ASSOC);
												$total_reg = count($res);
												if($total_reg > 0){

													for($i=0; $i < $total_reg; $i++){
														foreach ($res[$i] as $key => $value){} 

															$nome_reg = $res[$i]['nome'];
														$id_reg = $res[$i]['id'];
														?>
														<option value="<?php echo $id_reg ?>"><?php echo $nome_reg ?></option>

													<?php }} ?>
												</select>
											</div>
										</div>
									</div>
							

						<div class="row">
								<div class="col-md-12 mb-3">
								<label for="exampleFormControlInput1" class="">Observações</label>
								<textarea class="form-control textarea" id="obs" name="obs" maxlength="3000"></textarea>
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
											<td class="bg-warning alert-warning">Dias</td>
											<td><span id="dias_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning">Horário</td>
											<td><span id="hora_dados"></span></td>
										</tr>

									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Ínicio</td>
										<td><span id="inicio_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Término</td>
										<td><span id="termino_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Status</td>
										<td><span id="status_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Local</td>
										<td><span id="local_dados"></span></td>
									</tr>


									<tr>
										<td class="bg-warning alert-warning w_150">Pastor Responsável</td>
										<td><span id="pastor_dados"></span></td>
									</tr>


									<tr>
										<td class="bg-warning alert-warning w_150">Ministrador</td>
										<td><span id="coordenador_dados"></span></td>
									</tr>


									<tr>
										<td class="bg-warning alert-warning w_150">Ministrador 2</td>
										<td><span id="lider1_dados"></span></td>
									</tr>


									<tr>
										<td class="bg-warning alert-warning w_150">Ministrador 3</td>
										<td><span id="lider2_dados"></span></td>
									</tr>


									<tr>
										<td class="bg-warning alert-warning w_150">OBS</td>
										<td><span id="obs_dados"></span></td>
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
								<img src="../img/arquivos/sem-foto.png"  width="60px" id="target-arquivos">									
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




<!-- Modal -->
<div class="modal fade" id="modalAddMembros" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Adicionar Membros - <span id="nome-add"></span></span></h5>
							<button id="btn-fechar-arquivos" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
						</div>
						<form id="form-add" method="post">
							<div class="modal-body">

								<div class="row">
									<div class="col-md-4">
										<div class="mb-3" id="listar-membros">
											
											</div>
										</div>
										<div class="col-md-8" id="listar-membros-add">

										</div>
									</div>



									<small><div id="mensagem-add" align="center"></div></small>

									<input type="hidden" class="form-control" name="id-add"  id="id-add">
									<input type="hidden" class="form-control" name="id-igreja"  id="id-ig">


								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-add">Fechar</button>
								<button type="submit" id="btn_salvar2" class="btn btn-primary">Salvar</button>
								</div>
							</form>
						</div>
					</div>
				</div>




<!-- Modal -->
			<div class="modal fade" id="modalAndamento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Lançar Andamento - <span id="nome-andamento"></span></span></h5>
							<button btn="btn-fechar-andamento" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<form id="form-andamento" method="post">
							<div class="modal-body">

								
								<div class="row">
									<div class="col-md-3">
										<div class="mb-3">
								<label >Data Ministração </label>
								<input type="date" class="form-control" id="data_and" name="data_and" value="<?php echo date('Y-m-d') ?>" required>
							</div>
									</div>	


									<div class="col-md-3">
										<div class="mb-3">
								<label >Quant Membros </label>
								<input type="number" class="form-control" id="membros" name="membros" value="" required>
							</div>
									</div>	




									<div class="col-md-6">
								
								<div class="mb-3">
									<label >Ministrador</label>
									<select class="form-select sel30" id="ministrador_andamento" name="ministrador" style="width:100%;">
										<option value="0"> Membro / Pastor</option>

									

										<?php 
										$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' and ativo = 'Sim' order by  nome asc");
										$res = $query->fetchAll(PDO::FETCH_ASSOC);
										$total_reg = count($res);
										if($total_reg > 0){

											for($i=0; $i < $total_reg; $i++){
												foreach ($res[$i] as $key => $value){} 

													$nome_reg = $res[$i]['nome'];
												$id_reg = $res[$i]['id'];
												?>
												<option value="<?php echo $id_reg ?>"><?php echo $nome_reg ?></option>

											<?php }} ?>
										</select>
									</div>
									
								</div>


								</div>



									<div class="row">
							
							<div class="col-md-6">
										<div class="mb-3">
											<label >Arquivo <small>(*pdf, *word, *imagens, *rar ou zip)</small></label>
											<input type="file" class="form-control" id="foto" name="imagem" onChange="carregarImg();">
										</div>
									</div>
									<div class="col-md-2">
										<div id="divImg" class="mt-4">
											<img src="../img/documentos/sem-foto.jpg"  width="50px" id="target">									
										</div>
									</div>
								</div>


								<div class="row">
									<div class="col-md-12">
										<div class="mb-3">
								<label >Observações </label>
								<input type="text" class="form-control" id="obs_andamento" name="obs_andamento" value="" >
							</div>
									</div>
								</div>


								<div class="row">
									<div class="col-md-12">
											<div class="mb-3">
							<label >Conteúdo Ministrado <small><small>(Resumo) </small></small></label>
							<textarea type="text" class="form-control textarea" id="conteudo" name="conteudo" ></textarea>
						</div>
							
									</div>
								</div>


									<small><div id="mensagem-andamento" align="center"></div></small>

									<input type="hidden" class="form-control" name="id"  id="id-andamento">
									<input type="hidden" class="form-control" name="id-igreja"  id="id-ig-andamento">


								</div>
								<div class="modal-footer">
									
									<button type="submit" class="btn btn-primary">Adicionar</button>
								</div>
							</form>
						</div>
					</div>
				</div>





			<!-- Modal -->
			<div class="modal fade" id="modalListarAndamento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Andamento - <span id="nome-andamento-listar"></span></span></h5>
							<button btn="btn-fechar-listar-andamento" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						
							<div class="modal-body">
								<div id="listar_andamento">
									
								</div>
							</div>
								
						</div>
					</div>
				</div>








<!-- Modal -->
			<div class="modal fade" id="modalDadosAndamento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Conteúdo Ministrado <span id="nome-andamento-listar"></span></span></h5>
							<button btn="btn-fechar-listar-dados-andamento" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						
							<div class="modal-body">
								<div id="listar_dados_andamento">
									
								</div>
							</div>
								
						</div>
					</div>
				</div>





				<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
					$(document).ready(function() {
						$('.sel2').select2({
							placeholder: 'Selecione um Registro',
							dropdownParent: $('#modalForm')
						});

						$('.sel30').select2({
							placeholder: 'Selecione um Registro',
							dropdownParent: $('#modalAndamento')
						});
					});
				</script>


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
					$('#target-arquivos').attr('src','../img/arquivos/sem-foto.png');
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
			$('#target-arquivos').attr('src', "../img/pdf.png");
			return;
		}

		if(resultado[1] === 'rar' || resultado[1] === 'zip'){
			$('#target-arquivos').attr('src', "../img/rar.png");
			return;
		}

		if(resultado[1] === 'doc' || resultado[1] === 'docx' || resultado[1] === 'txt'){
			$('#target-arquivos').attr('src', "../img/word.png");
			return;
		}


		if(resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls'){
			$('#target-arquivos').attr('src', "../img/excel.png");
			return;
		}


		if(resultado[1] === 'xml'){
			$('#target-arquivos').attr('src', "../img/xml.png");
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




	
	$("#form-add").submit(function () {
						
						event.preventDefault();
						var formData = new FormData(this);

						var igreja = "<?=$id_igreja?>";
						var celula = $('#id-add').val();
						
						$.ajax({
							 url: 'paginas/' + pag + "/add-membros.php",
						
							type: 'POST',
							data: formData,

							success: function (mensagem) {
								

								$('#mensagem-add').text('');
								$('#mensagem-add').removeClass()
								if (mensagem.trim() == "Adicionado com Sucesso") {



									listarMembrosCB(celula, igreja);
									listarMembrosAdd(celula, igreja);
									
								} else {

									$('#mensagem-add').addClass('text-danger')
									$('#mensagem-add').text(mensagem)
								}


							},

							cache: false,
							contentType: false,
							processData: false,

						});

					});

					
</script>



<script type="text/javascript">
	$("#form-andamento").submit(function () {
	event.preventDefault();	
	//nicEditors.findEditor('conteudo').saveContent();
	var formData = new FormData(this);

	$.ajax({
		url: 'paginas/' + pag + "/andamento.php",
		type: 'POST',
		data: formData,

		success: function (mensagem) {
			$('#mensagem-andamento').text('');
			$('#mensagem-andamento').removeClass()
			if (mensagem.trim() == "Salvo com Sucesso") {
                    $('#membros').val('');
                    $('#obs_andamento').val('');
                    //nicEditors.findEditor("conteudo").setContent('');
                     $('#btn-fechar-andamento').click();
                     //mensagemSalvar();

                     var id = $('#id-andamento').val();
                     listar_andamento(id, nome);
                    
                     
                    
                     
                } else {

                	$('#mensagem-andamento').addClass('text-danger')
                	$('#mensagem-andamento').text(mensagem)
                }


            },

            cache: false,
            contentType: false,
            processData: false,
            
        });

});
</script>



<script type="text/javascript">
	

$("#form-excluir-andamento").submit(function () {
    event.preventDefault();
    var formData = new FormData(this);
    
    $.ajax({
        url: 'paginas/' + pag + "/excluir_andamento.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-excluir-andamento').text('');
            $('#mensagem-excluir-andamento').removeClass()
            if (mensagem.trim() == "Excluído com Sucesso") {
                $('#btn-fechar-excluir-andamento').click();
                //mensagemExcluir()
                
                var id = $('#id-andamento').val();
                     listar_andamento(id, '');

                 window.location= pag;
               		
            } else {

                $('#mensagem-excluir-andamento').addClass('text-danger')
                $('#mensagem-excluir-andamento').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});

</script>