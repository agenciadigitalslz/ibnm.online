<?php 
$pag = 'alertas';

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
						<div class="col-md-4 mb-2">							
								<label>Título</label>
								<input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título do Alerta" required>							
						</div>


							<div class="col-md-5 mb-2">							
								<label>Link <small>(Se Existir)</small></label>
								<input type="text" class="form-control" id="link" name="link" placeholder="Insira o Link">							
						</div>



							<div class="col-md-3 mb-2">							
								<label>Data Expiração</label>
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
									<label>Foto <small>Indicado(200x200)</small></label> 
									<input class="form-control" type="file" name="imagem" onChange="carregarImg();" id="foto">
								</div>						
							</div>
							<div class="col-md-4 mb-2 col-4">
								<img src="images/sem-foto.png"  width="80px" id="target">	
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
		<div class="modal-dialog">
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
											<td class="bg-warning alert-warning">Descrição</td>
											<td><span id="descricao_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning">Link</td>
											<td> <a id="link_ref" href="" target="_blank"><span id="link_dados"></span></a></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Data Expiração</td>
											<td><span id="data_dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Usuário Cadastro</td>
											<td><span id="usuario_dados"></span></td>
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



