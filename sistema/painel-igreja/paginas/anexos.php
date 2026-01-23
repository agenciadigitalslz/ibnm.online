<?php 
$pag = 'anexos';

if(@$cargos == 'ocultar'){
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
			<form id="form">
			<div class="modal-body">
				
				

					<div class="row">
						<div class="col-md-6 mb-2">							
								<label>Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Documento" required>							
						</div>



							<div class="col-md-6 mb-2">							
								<label>Data</label>
								<input type="date" class="form-control" id="data1" name="data1" >							
						</div>
				 	    </div>

						<div class="row">
							<div class="col-md-12 mb-2">							
								<label>Descrição <small>(Max 500 Caracteres)</small></label>
								<textarea maxlength="500" class="form-control" id="descricao" name="descricao"></textarea>							
						</div>
						</div>

						<div class="row">
									
								<div class="col-md-8 mb-2 col-8" >						
								<div class="form-group"> 
									<label>Arquivo <small>(*pdf, *word, *imagens, *rar ou zip)</small></label> 
									<input class="form-control" type="file" name="arquivo" onChange="carregarImgArquivos();" id="arquivo">
								</div>
							</div>
							<div class="col-md-4 mb-2 col-4">
								<img src="../img/sem-foto.png"  width="80px" id="target">	
							</div>

					</div>

						<div class="col-md-12" style="margin-top: 22px">							
								<button id="btn_salvar" type="submit" class="btn btn-primary">Salvar</button>					
						</div>

						
					</div>

			
					<input type="hidden" class="form-control" id="id" name="id">
					<input type="hidden" id="igreja" name="igreja" value="<?php echo $id_igreja ?>">				

				<br>
				<small><div id="mensagem" align="center"></div></small>
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
											<td class="bg-warning alert-warning">Descrição</td>
											<td><span id="descricao_dados"></span></td>
										</tr>


										<tr>
											<td class="bg-warning alert-warning w_150">Data</td>
											<td><span id="data_dados"></span></td>
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
											<td align="center"><img src="" id="arquivo_dados" width="200px"></td>
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
		function carregarImgArquivos() {

			var target = document.getElementById('target');
			var file = document.querySelector("#arquivo").files[0];

			var arquivo = file['name'];
			resultado = arquivo.split(".", 2);

			

			if(resultado[1] === 'pdf'){
				
				$('#target').attr('src', "../img/pdf.png");
				return;
			}else{
				
			}

			if(resultado[1] === 'rar' || resultado[1] === 'zip'){
				$('#target').attr('src', "../img/rar.png");
				return;
			}

			if(resultado[1] === 'doc' || resultado[1] === 'docx' || resultado[1] === 'txt'){
				$('#target').attr('src', "../img/word.png");
				return;
			}


			if(resultado[1] === 'xlsx' || resultado[1] === 'xlsm' || resultado[1] === 'xls'){
				$('#target').attr('src', "../img/excel.png");
				return;
			}


			if(resultado[1] === 'xml'){
				$('#target').attr('src', "../img/xml.png");
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



