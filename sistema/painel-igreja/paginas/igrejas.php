<?php 
$pag = 'igrejas';

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
			<form id="form_igreja">
			<div class="modal-body">
				

					<div class="row">
						<div class="col-md-3 mb-2 col-6">							
								<label>Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Seu Nome" required>							
						</div>

						<div class="col-md-3 col-6">							
								<label>Telefone</label>
								<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Seu Telefone">							
						</div>

							<div class="col-md-3 mb-2">							
								<label>Endereço</label>
								<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Seu Endereço" >							
						</div>

						<div class="col-md-3">
							<div class="mb-3">
								<label >Prebenda %</label>
								<input type="number" class="form-control" id="prebenda" name="prebenda" placeholder="Valor em %">
							</div>
						</div>

								
					</div>


					<div class="row">

						<div class="col-md-4">
							<div class="mb-3">
								<label class="">Pastor Responsável</label>
								<select class="form-select sel2" id="pastor" name="pastor" style="width:100%;">
									<?php 
									$query = $pdo->query("SELECT * FROM pastores order by nome asc");
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

						<div class="col-md-8">
								<div class="mb-3">
									<label for="exampleFormControlInput1" class="">Vídeo Página Inicial do Site <small><small>(Url Incorporada Youtube, o src do iframe)</small></small></label>
									<input type="text" class="form-control" id="video" name="video" placeholder="https://www.youtube.com/embed/Y7sfHr1alEI">
								</div>
							</div>
						
						
					

						
					</div>

					<div class="row">
						<div class="col-md-6 mb-2">							
							<label>Email</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="Seu Email"  required>							
						</div>

						<div class="col-md-6">
									<div class="mb-3">
								<label for="exampleFormControlInput1" class="">Url Site (Tudo Junto)</label>
									<input maxlength="50" type="text" class="form-control" id="url" name="url" placeholder="Ex: serraverde">
									</div>								
								</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="mb-3">
								<label for="exampleFormControlInput1" class="">Youtube (link)</label>
									<input type="text" class="form-control" id="youtube" name="youtube" placeholder="Link do canal do youtube">		</div>						
								</div>

								<div class="col-md-4">
									<div class="mb-3">
								<label for="exampleFormControlInput1" class="">Instagram</label>
									<input type="text" class="form-control" id="instagram" name="instagram" placeholder="Link do Instagram">		</div>						
								</div>


								<div class="col-md-4">
									<div class="mb-3">
								<label for="exampleFormControlInput1" class="">Facebook</label>
									<input type="text" class="form-control" id="facebook" name="facebook" placeholder="Link do Facebook">			</div>					
								</div>

							
							
							</div>

							<div class="row">
								<div class="col-md-12 mb-3">
								<label for="exampleFormControlInput1" class="">Descrição da Igreja <small>(Texto apresentado no site) </small></label>
								<textarea class=" textarea_ed" id="descricao" name="descricao" ></textarea>
							</div>

							</div>

							<div class="row">
								<div class="col-md-12 mb-3">
								<label for="exampleFormControlInput1" class="">Observações</label>
								<input class=" form-control" id="obs" name="obs" maxlength="3000">
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
					<h4 class="modal-title" id="exampleModalLabel"><span id="titulo-dados"></span></h4>
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
											<td><span id="telefone-dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning">Email</td>
											<td><span id="email-dados"></span></td>
										</tr>										

										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Endereço</td>
											<td><span id="endereco-dados"></span></td>
										</tr>


										<tr>
											<td class="bg-warning alert-warning w_150">Data de Cadastro</td>
											<td><span id="cadastro-dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Matriz</td>
											<td><span id="matriz-dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Pastor Responsável</td>
											<td><span id="pastor-dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Descrição</td>
											<td><span id="descricao-dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Observações</td>
											<td><span id="obs-dados"></span></td>
										</tr>

										<tr>
											<td class="bg-warning alert-warning w_150">Prebenda</td>
											<td><span id="prebenda_dados"></span>%</td>
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
<div class="modal fade" id="modalImagens" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title" id="exampleModalLabel"><span id="tituloModal">Imagens da Igreja <span id="nome-imagem"></span></span></h5>
				<button id="btn-fechar-imagens" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
			</div>
			
				<form id="form-img" method="post">
				<div class="modal-body">

					
					<div class="row">
						<div class="col-md-6">
							<div class="col-md-6">
								<div class="mb-3">
									<label>Logo Relatório JPG</label>
									<input type="file" class="form-control-file" id="imagemlogojpg" name="logojpg" onChange="carregarImglogojpg();">
								</div>
							</div>
							<div class="col-md-6">
								<div id="divImg" class="mt-4">
									<img src="../img/igrejas/sem-foto.jpg"  width="170px" id="targetlogojpg">									
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="col-md-6">
								<div class="mb-3">
									<label>Cabeçalho Relatório JPG</label>
									<input type="file" class="form-control-file" id="imagemcabjpg" name="cabjpg" onChange="carregarImgcabjpg();">
								</div>
							</div>
							<div class="col-md-6">
								<div id="divImg" class="mt-4">
									<img src="../img/igrejas/sem-foto.jpg"  width="170px" id="targetcabjpg">									
								</div>
							</div>
						</div>

					</div>


					<div class="row">
						<div class="col-md-6">
							<div class="col-md-6">
								<div class="mb-3">
									<label>Cabeçalho Carteirinha JPG</label>
									<input type="file" class="form-control-file" id="imagemcartjpg" name="cartjpg" onChange="carregarImgcartjpg();">
								</div>
							</div>
							<div class="col-md-6">
								<div id="divImg" class="mt-4">
									<img src="../img/igrejas/sem-foto.jpg"  width="170px" id="targetcartjpg">									
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="col-md-6">
								<div class="mb-3">
									<label>Logo Painel</label>
									<input type="file" class="form-control-file" id="imagempaineljpg" name="paineljpg" onChange="carregarImgpaineljpg();">
								</div>
							</div>
							<div class="col-md-6">
								<div id="divImg" class="mt-4">
									<img src="../img/igrejas/sem-foto.jpg"  width="170px" id="targetpaineljpg">									
								</div>
							</div>
						</div>

						<div class="col-md-6">

						</div>

					</div>
					

					<small><div id="mensagem-img" align="center"></div></small>

					<input type="hidden" class="form-control" name="id-img"  id="id-img">


				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-img">Fechar</button>
					<button type="submit" class="btn btn-primary">Salvar</button>
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
	
function carregarImglogojpg() {
    var target = document.getElementById('targetlogojpg');
    var file = document.querySelector("#imagemlogojpg").files[0];
    
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


     function carregarImgpaineljpg() {
    var target = document.getElementById('targetpaineljpg');
    var file = document.querySelector("#imagempaineljpg").files[0];
    
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




    function carregarImgcabjpg() {
    var target = document.getElementById('targetcabjpg');
    var file = document.querySelector("#imagemcabjpg").files[0];
    
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



    function carregarImgcartjpg() {
    var target = document.getElementById('targetcartjpg');
    var file = document.querySelector("#imagemcartjpg").files[0];
    
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
	
$("#form-img").submit(function () {
    event.preventDefault();
    var formData = new FormData(this);
    
    $.ajax({
        url: 'paginas/' + pag + "/imagens.php",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-img').text('');
            $('#mensagem-img').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
                    //$('#nome').val('');
                    //$('#cpf').val('');
                    $('#btn-fechar-img').click();
                     window.location= pag;
                } else {

                    $('#mensagem-img').addClass('text-danger')
                    $('#mensagem-img').text(mensagem)
                }


            },

            cache: false,
            contentType: false,
            processData: false,
            
        });

});

</script>



<script type="text/javascript">
	
$("#form_igreja").submit(function () {

    event.preventDefault();
     nicEditors.findEditor('descricao').saveContent();
	
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
                listar();

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

</script>


<script src="//js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>