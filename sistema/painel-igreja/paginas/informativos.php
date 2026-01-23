<?php 
$pag = 'informativos';

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
						<div class="col-md-4">
							<div class="mb-3">
								<label>Preletor</label>
								<input type="text" class="form-control" id="preletor" name="preletor" placeholder="Nome do Preletor / Pregador" required>
							</div>
						</div>
						<div class="col-md-3">
							<div class="mb-3">
								<label>Data</label>
								<input type="date" class="form-control" id="data2" name="data2"  required>
							</div>
						</div>

						<div class="col-md-5">
							<div class="mb-3">
								<label>Texto Base</label>
								<input maxlength="255" type="text" class="form-control" id="texto_base" name="texto_base" placeholder="Insira o Texto Base" required>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="mb-3">
								<label>Tema</label>
								<input maxlength="255" type="text" class="form-control" id="tema" name="tema" placeholder="Tema do Culto" required>
							</div>
						</div>
					</div>

					<div class="row">
						

						<div class="col-md-6">
							<div class="mb-3">
								<label>Evento</label>
								<input type="text" class="form-control" id="evento" name="evento" placeholder="Culto de Domingo" required>
							</div>
						</div>

						<div class="col-md-2">
							<div class="mb-3">
								<label>Horário</label>
								<input type="time" class="form-control" id="horario" name="horario">
							</div>
						</div>

						<div class="col-md-4">
							<div class="mb-3">
								<label>Pastor Responsável</label>
								<select class="form-select sel2" id="pastor_responsavel" name="pastor_responsavel" style="width:100%;" required>										
										<?php 
										$query = $pdo->query("SELECT * FROM pastores where igreja = '$id_igreja' order by id asc");
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
						<div class="col-md-8">
							<div class="mb-3">
								<label>Demais Pastores</label>
								<input type="text" class="form-control" id="pastores" name="pastores" placeholder="Pastor Marcos / Pastor Paulo...">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label>Líder Louvor</label>
								<input type="text" class="form-control" id="lider_louvor" name="lider_louvor">
							</div>
						</div>


					</div>



					<div class="row">
						<div class="col-md-7">
							<div class="mb-3">
								<label>Obreiros</label>
								<input type="text" class="form-control" id="obreiros" name="obreiros" placeholder="Marcos / Paulo...">
							</div>
						</div>
						<div class="col-md-5">
							<div class="mb-3">
								<label>Apoios</label>
								<input type="text" class="form-control" id="apoio" name="apoio" placeholder="Sandro / Márcia">
							</div>
						</div>


					</div>




					<div class="row">
						<div class="col-md-4">
							<div class="mb-3">
								<label>Abertura</label>
								<input type="text" class="form-control" id="abertura" name="abertura" placeholder="Responsável pela Abertura">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label>Recados</label>
								<input type="text" class="form-control" id="recado" name="recado" placeholder="Responsável pelos Recados">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label>Ofertas</label>
								<input type="text" class="form-control" id="oferta" name="oferta" placeholder="Responsável pelas Ofertas">
							</div>
						</div>


					</div>




					<div class="row">
						<div class="col-md-4">
							<div class="mb-3">
								<label>Recepção</label>
								<input type="text" class="form-control" id="recepcao" name="recepcao" placeholder="Responsável pela Recepção">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label>Berçário</label>
								<input type="text" class="form-control" id="bercario" name="bercario" placeholder="Responsável pelo Berçário">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label>Escolinha</label>
								<input type="text" class="form-control" id="escolinha" name="escolinha" placeholder="Responsável pela Escolinha">
							</div>
						</div>


					</div>


					<div class="row">
						<div class="col-md-12">
							<div class="mb-3">
								<label>Observações</label>
								<input maxlength="1000" type="text" class="form-control" id="obs" name="obs" placeholder="">
							</div>
						</div>
					</div>

					<hr>


					<div class="row">
						<div class="col-md-4">
							<div class="mb-3">
								<label>Total Membros</label>
								<input type="number" class="form-control" id="membros" name="membros" placeholder="Membros Presentes">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label>Total Visitantes</label>
								<input type="number" class="form-control" id="visitantes" name="visitantes" placeholder="Visitantes Presentes">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label>Total de Conversões</label>
								<input type="number" class="form-control" id="conversoes" name="conversoes" placeholder="Total de Conversões">
							</div>
						</div>


					</div>




						<div class="row">
						<div class="col-md-4">
							<div class="mb-3">
								<label>Total Ofertas</label>
								<input type="text" class="form-control" id="total_ofertas" name="total_ofertas" placeholder="Total de Ofertas">
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3">
								<label>Total Dízimos</label>
								<input type="text" class="form-control" id="total_dizimos" name="total_dizimos" placeholder="Total de Dízimos">
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



