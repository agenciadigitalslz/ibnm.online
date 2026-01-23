<?php 
$pag = 'fechamentos';

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
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				 <button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			<form id="form">
			<div class="modal-body">
				
				

					<div class="row">
						<div class="col-md-12">
							<div class="mb-3">
								<label >Qualquer Data do Mês Fechamento </label>
								<input type="date"  class="form-control" id="data_fec" name="data_fec" required value="<?php echo date('Y-m-d') ?>">
							</div>
						</div>
						
					</div>


					<div class="col-md-12" style="margin-top: 22px">							
								<button id="btn_salvar" type="submit" class="btn btn-primary">Salvar</button>					
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



