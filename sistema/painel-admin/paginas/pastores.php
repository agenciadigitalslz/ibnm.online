<?php 
$pag = 'pastores';

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
						<div class="col-md-3 mb-2 col-6">							
								<label>Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Seu Nome" required>							
						</div>

						<div class="col-md-3 mb-2 col-6">							
								<label>CPF</label>
								<input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" >							
						</div>

							<div class="col-md-3">							
								<label>Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" >							
						</div>

						<div class="col-md-3 mb-2">							
								<label>Nascimento</label>
								<input type="text" class="form-control" id="data_nasc" name="data_nasc" placeholder="" >							
						</div>
								
					</div>


					<div class="row">	

						<div class="col-md-4 col-6">							
								<label>Telefone</label>
								<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Seu Telefone">							
						</div>
						
						<div class="col-md-4 mb-2">							
								<label>Endereço</label>
								<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Seu Endereço" >							
						</div>

						<div class="col-md-4 mb-2 col-6">							
								<label>Pix</label>
								<input type="text" class="form-control" id="pix" name="pix" placeholder="CPF xxxxx" >							
						</div>

					

						
					</div>

					<div class="row">
						<div class="col-md-3">

								<label >Igreja</label>
								<select class="form-select sel2" id="igreja" name="igreja" style="width:100%;">
									<?php 
									$query = $pdo->query("SELECT * FROM igrejas order by matriz desc, nome asc");
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

							<div class="col-md-3 mb-2 col-6">							
								<label>Prebenda</label>
								<input type="text" class="form-control" id="prebenda" name="prebenda" placeholder="Valor em %" >							
						</div>
					
						<div class="col-md-3 mb-2 col-8" >						
								<div class="form-group"> 
									<label>Foto <small>Indicado(200x200)</small></label> 
									<input class="form-control" type="file" name="imagem" onChange="carregarImg();" id="foto">
								</div>						
							</div>
							<div class="col-md-3 mb-2 col-4">
								<img src="images/sem-foto.png"  width="80px" id="target">	
							</div>
					</div>

					<div class="row">
								<div class="col-md-12 mb-3">
								<label for="exampleFormControlInput1" class="">Observações</label>
								<textarea class="form-control textarea" id="obs" name="obs" maxlength="3000"></textarea>
							</div>
						</div>

					
					

					<input type="hidden" class="form-control" id="id" name="id">					

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
											<td class="bg-warning alert-warning">Telefone</td>
											<td><span id="telefone_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning">Email</td>
											<td><span id="email_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">CPF</td>
											<td><span id="cpf_dados"></span></td>
										</tr>

										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Endereço</td>
											<td><span id="endereco_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Data de Nascimento</td>
											<td><span id="nasc_dados"></span></td>
										</tr>


										<tr>
											<td class="bg-warning alert-warning w_150">Igreja</td>
											<td><span id="igreja_dados"></span></td>
										</tr>


										<tr>
											<td class="bg-warning alert-warning w_150">Prebenda</td>
											<td><span id="prebenda_dados"></span>%</td>
										</tr>


										<tr>
											<td class="bg-warning alert-warning w_150">Observações</td>
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



<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<script>
		$(document).ready(function() {
			$('.sel2').select2({
			//placeholder: 'Selecione um Cliente',
				dropdownParent: $('#modalForm')
			});
		});
	</script>




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
