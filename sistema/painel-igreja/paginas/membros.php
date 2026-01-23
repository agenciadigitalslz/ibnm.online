<?php 
$pag = 'membros';

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
							<input type="text" class="form-control" id="nome" name="nome" placeholder="Seu Nome" required>							
						</div>

						<div class="col-md-4 mb-2 col-6">							
							<label>CPF</label>
							<input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF" >							
						</div>

						<div class="col-md-4 mb-2 col-6">							
							<label>Email</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="Email" >							
						</div>
					</div>

					<div class="row">
						<div class="col-md-3 mb-2">							
							<label>Nascimento</label>
							<input type="text" class="form-control" id="data_nasc" name="data_nasc" value="">							
						</div>

						




						<div class="col-md-3 col-6">							
							<label>Telefone</label>
							<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Seu Telefone">							
						</div>
						

						<div class="col-md-3">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="">Estado Cívil</label>
								<select class="form-select " id="estado_civil" name="estado_civil" style="width:100%;">
									<option value="0">Selecione um Estado</option>

									<option value="Solteiro">Solteiro</option>
									<option value="Casado">Casado</option>
								</select>
							</div>

						</div>
						

						<div class="col-md-3">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="">Cargo Ministerial</label>
								<select class="form-select " id="cargo" name="cargo" style="width:100%;">
									<option value="0">Selecione um Cargo</option>
									<?php 
									$query = $pdo->query("SELECT * FROM cargos order by id asc");
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
									<label for="exampleFormControlInput1" class="">Data Batismo</label>
									<input type="text" class="form-control" id="data_bat" name="data_bat">
								</div>
							</div>							




							<div class="col-md-4">
								<div class="mb-3">
									<label>Membresia</label>
									<input type="text" class="form-control" id="membresia" name="membresia">
								</div>
							</div>


							<div class="col-md-4">
								<div class="mb-3">
									<label>RG</label>
									<input type="text" class="form-control" id="rg" name="rg" placeholder="Insira o RG">
								</div>
							</div>

						</div>



						<div class="row">

						<div class="col-md-2 mb-2">							
							<label>CEP</label>
							<input type="text" class="form-control" id="cep" name="cep" placeholder="CEP" onblur="pesquisacep(this.value);">							
						</div>

						<div class="col-md-5 mb-2">							
							<label>Rua</label>
							<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Rua" >							
						</div>

						<div class="col-md-2 mb-2">							
							<label>Número</label>
							<input type="text" class="form-control" id="numero" name="numero" placeholder="Número" >							
						</div>

						<div class="col-md-3 mb-2">							
							<label>Complemento</label>
							<input type="text" class="form-control" id="complemento" name="complemento" placeholder="Se houver" >							
						</div>



					</div>


					<div class="row">

						<div class="col-md-4 mb-2">							
							<label>Bairro</label>
							<input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro" >							
						</div>

						<div class="col-md-5 mb-2">							
							<label>Cidade</label>
							<input type="text" class="form-control" id="cidade" name="cidade" placeholder="Cidade" >							
						</div>

						<div class="col-md-3 mb-2">							
							<label>Estado</label>
							<select class="form-select" id="estado" name="estado">
							<option value="">Selecionar</option>
							<option value="AC">Acre</option>
							<option value="AL">Alagoas</option>
							<option value="AP">Amapá</option>
							<option value="AM">Amazonas</option>
							<option value="BA">Bahia</option>
							<option value="CE">Ceará</option>
							<option value="DF">Distrito Federal</option>
							<option value="ES">Espírito Santo</option>
							<option value="GO">Goiás</option>
							<option value="MA">Maranhão</option>
							<option value="MT">Mato Grosso</option>
							<option value="MS">Mato Grosso do Sul</option>
							<option value="MG">Minas Gerais</option>
							<option value="PA">Pará</option>
							<option value="PB">Paraíba</option>
							<option value="PR">Paraná</option>
							<option value="PE">Pernambuco</option>
							<option value="PI">Piauí</option>
							<option value="RJ">Rio de Janeiro</option>
							<option value="RN">Rio Grande do Norte</option>
							<option value="RS">Rio Grande do Sul</option>
							<option value="RO">Rondônia</option>
							<option value="RR">Roraima</option>
							<option value="SC">Santa Catarina</option>
							<option value="SP">São Paulo</option>
							<option value="SE">Sergipe</option>
							<option value="TO">Tocantins</option>
							<option value="EX">Estrangeiro</option>
						</select>												
					</div>

					
				</div>


						
						<div class="row">

							

							<div class="col-md-6">
								<div class="mb-3">
									<label>Nacionalidade</label>
									<input type="text" class="form-control" id="nacionalidade" name="nacionalidade" placeholder="Brasileiro">
								</div>
							</div>


							<div class="col-md-6">
								<div class="mb-3">
									<label>Naturalidade</label>
									<input type="text" class="form-control" id="naturalidade" name="naturalidade" placeholder="Belo Horizonte">
								</div>
							</div>

						</div>



						<div class="row">

							<div class="col-md-6">
								<div class="mb-3">
									<label>Nome do Pai</label>
									<input type="text" class="form-control" id="nome_pai" name="nome_pai" placeholder="Nome Pai">
								</div>
							</div>


							<div class="col-md-6">
								<div class="mb-3">
									<label>Nome da Mãe</label>
									<input type="text" class="form-control" id="nome_mae" name="nome_mae" placeholder="Nome da Mãe">
								</div>
							</div>


						</div>



						<div class="row">

							<div class="col-md-4">
								<div class="mb-3">
									<label>Profissão</label>
									<input type="text" class="form-control" id="profissao" name="profissao" placeholder="">
								</div>
							</div>

							<div class="col-md-5">
								<div class="mb-3">
									<label>Cônjuge</label>
									<input type="text" class="form-control" id="conjuge" name="conjuge" placeholder="">
								</div>
							</div>

								<div class="col-md-3">
								<div class="mb-3">
									<label>Data Consagração</label>
									<input type="text" class="form-control" id="data_consagracao" name="data_consagracao" placeholder="">
								</div>
							</div>				


						</div>



						<div class="row">

							
								<div class="col-md-5">
								<div class="mb-3">
									<label>Igreja Anterior</label>
									<input type="text" class="form-control" id="igreja_anterior" name="igreja_anterior" placeholder="">
								</div>
							</div>


								<div class="col-md-4">
								<div class="mb-3">
									<label>Cidade Anterior</label>
									<input type="text" class="form-control" id="cidade_anterior" name="cidade_anterior" placeholder="">
								</div>
							</div>

							<div class="col-md-3">
								<div class="mb-3">
									<label>Título eleitor</label>
									<input type="text" class="form-control" id="titulo_eleitor" name="titulo_eleitor" placeholder="">
								</div>
							</div>


						</div>


						<div class="row">
							

							<div class="col-md-4">
								<div class="mb-3">
									<label>Cidade Votação</label>
									<input type="text" class="form-control" id="cidade_votacao" name="cidade_votacao" placeholder="">
								</div>
							</div>

							<div class="col-md-4">
								<div class="mb-3">
									<label>Estado Votação</label>
									<input type="text" class="form-control" id="estado_votacao" name="estado_votacao" placeholder="">
								</div>
							</div>

							<div class="col-md-2">
								<div class="mb-3">
									<label>Zona</label>
									<input type="text" class="form-control" id="zona" name="zona" placeholder="">
								</div>
							</div>

							<div class="col-md-2">
								<div class="mb-3">
									<label>Sessão</label>
									<input type="text" class="form-control" id="sessao" name="sessao" placeholder="">
								</div>
							</div>
						</div>


						<div class="row">
							<div class="col-md-8 mb-2 col-8" >						
								<div class="form-group"> 
									<label>Foto <small>Indicado(200x200)</small></label> 
									<input class="form-control" type="file" name="imagem" onChange="carregarImg();" id="foto">
								</div>						
							</div>
							<div class="col-md-4 mb-2 col-4">
								<img src="../img/sem-foto.png"  width="80px" id="target">	
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
										<td><span id="endereco_dados"></span> <span id="numero_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Complemento</td>
										<td><span id="complemento_dados"></span> </td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Bairro</td>
										<td><span id="bairro_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Cidade</td>
										<td><span id="cidade_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Estado</td>
										<td><span id="estado_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">CEP</td>
										<td><span id="cep_dados"></span></td>
									</tr>


									<tr>
										<td class="bg-warning alert-warning w_150">Cargo</td>
										<td><span id="cargo_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Estado Civil</td>
										<td><span id="estado_civil_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Data de Nascimento</td>
										<td><span id="nasc_dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Data de Batismo</td>
										<td><span id="bat_dados"></span></td>
									</tr>



									<tr>
										<td class="bg-warning alert-warning w_150">Profissão</td>
										<td><span id="profissao-dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Cônjunge</td>
										<td><span id="conjuge-dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Data Consagração</td>
										<td><span id="data_consagracao-dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Igreja Anterior</td>
										<td><span id="igreja_anterior-dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Cidade Anterior</td>
										<td><span id="cidade_anterior-dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Título Eleitor</td>
										<td><span id="titulo_eleitor-dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Cidade Votação</td>
										<td><span id="cidade_votacao-dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Estado Votação</td>
										<td><span id="estado_votacao-dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Zona</td>
										<td><span id="zona-dados"></span></td>
									</tr>

									<tr>
										<td class="bg-warning alert-warning w_150">Sessão</td>
										<td><span id="sessao-dados"></span></td>
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


<!-- Modal Apresentacao -->
<div class="modal fade" id="modalApresentacao" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<span class="modal-title" id="exampleModalLabel"><span id="tituloModal">Certificado de Apresentação - <span id="nome-apres"></span></span></span>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">

				<div class="row">
					<div class="col-md-6" align="center">
						<a href="#" onclick="certApresentacao('menino')" title="Certificado de Menino">
							<img src="../img/apresentacao.jpg" width="70%">
						</a>
					</div>

					<div class="col-md-6" align="center">
						<a href="#" onclick="certApresentacao('menina')" title="Certificado de Menina">
							<img src="../img/apresentacao2.jpg" width="70%">
						</a>
					</div>
				</div>


				<small><div id="mensagem-apres" align="center"></div></small>

				<input type="hidden" class="form-control" name="id"  id="id-apres">


			</div>


		</div>
	</div>
</div>



<!-- Modal -->
<div class="modal fade" id="modalTransf" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Carta de Recomendaçao - <span id="nome-transf"></span></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form-obs" method="post" action="rel/rel_recomendacao_class.php" target="_blank">
				<div class="modal-body">

					<div class="mb-3">
						<label >Igreja</label>
						<input class="form-control" id="igreja-transf" name="igreja" placeholder="Nome da Igreja à Recomendar">
					</div>

					<div class="mb-3">
						<label >Observações (Máximo 2000 Caracteres)</label>
						<textarea class="form-control" id="obs-transf" name="obs" maxlength="2000" style="height:200px"></textarea>
					</div>



					<small><div id="mensagem-transf" align="center"></div></small>

					<input type="hidden" class="form-control" name="id"  id="id-transf">


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-transf">Fechar</button>
					<button type="submit" class="btn btn-primary">Gerar</button>
				</div>
			</form>
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




<!-- Modal Transferir membro -->
<div class="modal fade" id="modalTransferir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<span class="modal-title" id="exampleModalLabel"><span id="tituloModal">Transferir Membro - <span id="nome_transferir"></span></span></span>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="form-transferir" method="post" action="../rel/relRecomendacao.php" target="_blank">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-6" >
							<label>Transferir para Igreja</label>
							<select class="form-select" id="igreja_membro" name="igreja" style="width:100%;" required>
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

								<div class="col-md-6" >
									<div class="mb-3">
										<label>Obs</label>
										<input type="text" class="form-control" id="obs" name="obs" placeholder="Observações">
									</div>
								</div>
							</div>


							<small><div id="mensagem-transferir" align="center"></div></small>

							<input type="hidden" class="form-control" name="id"  id="id_transferir">
							<input type="hidden" class="form-control" name="igreja_saida"  id="igreja_saida" value="<?php echo $id_igreja ?>">


						</div>

						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-transferir">Fechar</button>
							<button type="submit" class="btn btn-primary">Transferir</button>
						</div>
					</form>


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
			//placeholder: 'Selecione um Cliente',
			dropdownParent: $('#modalForm')
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
		</script>



		<script type="text/javascript">

			$("#form-transferir").submit(function () {
				event.preventDefault();
				var formData = new FormData(this);

				$.ajax({
					url: 'paginas/' + pag + "/transferir.php",
					type: 'POST',
					data: formData,

					success: function (mensagem) {
						$('#mensagem-transferir').text('');
						$('#mensagem-transferir').removeClass()
						if (mensagem.trim() == "Salvo com Sucesso") {
                    //$('#nome').val('');
                    //$('#cpf').val('');
                    $('#btn-fechar-transferir').click();
                    listar();
                                        

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






<script>
    
    function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('endereco').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('cidade').value=("");
            document.getElementById('estado').value=("");
            //document.getElementById('ibge').value=("");
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('endereco').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('estado').value=(conteudo.uf);
            //document.getElementById('ibge').value=(conteudo.ibge);
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }
        
    function pesquisacep(valor) {

        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('endereco').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('estado').value="...";
                //document.getElementById('ibge').value="...";

                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    };

    </script>



    <script type="text/javascript">
    	function buscar(){
    		var ina = $("#ina").val();
    		listar(ina);
    	}
    </script>