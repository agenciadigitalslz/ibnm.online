<?php 
@session_start();
require_once("../conexao.php");
require_once("verificar.php");




$data_atual = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$data_inicio_mes = $ano_atual."-".$mes_atual."-01";
$data_inicio_ano = $ano_atual."-01-01";

$data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data_atual)));
$data_amanha = date('Y-m-d', strtotime("+1 days",strtotime($data_atual)));


if($mes_atual == '04' || $mes_atual == '06' || $mes_atual == '09' || $mes_atual == '11'){
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-30';
}else if($mes_atual == '02'){
	$bissexto = date('L', @mktime(0, 0, 0, 1, 1, $ano_atual));
	if($bissexto == 1){
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-29';
	}else{
		$data_final_mes = $ano_atual.'-'.$mes_atual.'-28';
	}

}else{
	$data_final_mes = $ano_atual.'-'.$mes_atual.'-31';
}



$pag_inicial = 'home';
if(@$_SESSION['nivel'] != 'Bispo'){	
	require_once("verificar_permissoes.php");
}


if(@$_GET['pagina'] != ""){
	$pagina = @$_GET['pagina'];
}else{
	$pagina = $pag_inicial;
}

$id_usuario = @$_SESSION['id'];
$query = $pdo->query("SELECT * from usuarios where id = '$id_usuario'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	$nome_usu = $res[0]['nome'];
	$email_usu = $res[0]['email'];
	$telefone_usu = $res[0]['telefone'];
	$senha_usu = $res[0]['senha'];
	$cpf_usu = $res[0]['cpf'];
	$nivel_usu = $res[0]['nivel'];
	$foto_usu = $res[0]['foto'];
}else{
	echo '<script>window.location="../"</script>';
	exit();
}


?>
<!DOCTYPE HTML>
<html lang="pt-BR" dir="ltr">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	

	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="Description" content="Fluxo Comunicação Inteligente">
	<meta name="Author" content="Samuel Lima">
	<meta name="Keywords" content="fluxo, comunicacao, inteligente, marketing, whatsapp"/>

	<title><?php echo $nome_sistema ?></title>

	<link rel="icon" href="../img/icone.png" type="image/x-icon"/>
	<link href="../assets/css/icons.css" rel="stylesheet">
	<link id="style" href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="../assets/css/style.css" rel="stylesheet">
	<link href="../assets/css/style-dark.css" rel="stylesheet">
	<link href="../assets/css/style-transparent.css" rel="stylesheet">
	<link href="../assets/css/skin-modes.css" rel="stylesheet" />
	<link href="../assets/css/animate.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link href="../assets/css/custom.css" rel="stylesheet" />
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/modernizr.custom.js"></script>		


	


</head>

<body class="ltr main-body app sidebar-mini">

	<?php if($mostrar_preloader == 'Sim'){ ?>
		<!-- GLOBAL-LOADER -->
		<div id="global-loader">
			<img src="../img/loader.gif" class="loader-img loader loader_mobile" alt="">
		</div>
		<!-- /GLOBAL-LOADER -->
	<?php } ?>

	<!-- Page -->
	<div class="page">

		<div>
			<!-- APP-HEADER1 -->
			<div class="main-header side-header sticky nav nav-item">
				<div class=" main-container container-fluid">
					<div class="main-header-left ">
						<div class="responsive-logo">
							<a href="index.php" class="header-logo">
								<img src="../img/foto-painel.png" class="mobile-logo logo-1" alt="logo" style="width:40% !important; margin-left: -120px !important">
								<img src="../img/foto-painel.png" class="mobile-logo dark-logo-1" alt="logo" style="width:40% !important; margin-left: -120px !important">
							</a>
						</div>
						<div class="app-sidebar__toggle" data-bs-toggle="sidebar">
							<a class="open-toggle" href="javascript:void(0);"><i class="header-icon fe fe-align-left" ></i></a>
							<a class="close-toggle" href="javascript:void(0);"><i class="header-icon fe fe-x"></i></a>
						</div>
						<div class="logo-horizontal">
							<a href="index.php" class="header-logo">
								<img src="../img/foto-painel.png" class="mobile-logo logo-1" alt="logo">
								<img src="../img/foto-painel.png" class="mobile-logo dark-logo-1" alt="logo">
							</a>
						</div>
						<div class="main-header-center ms-4 d-sm-none d-md-none d-lg-block form-group">
							
						</div>
					</div>
					<div class="main-header-right">
						
						<div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
							<div class="" id="navbarSupportedContent-4">
								<ul class="nav nav-item header-icons navbar-nav-right ms-auto">

									<li class="dropdown nav-item" style="opacity: 0">
										------------
									</li>

									
									
									<li class="dropdown nav-item ocultar_mobile">
										<a class="new nav-link theme-layout nav-link-bg layout-setting" >
											<span class="dark-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M20.742 13.045a8.088 8.088 0 0 1-2.077.271c-2.135 0-4.14-.83-5.646-2.336a8.025 8.025 0 0 1-2.064-7.723A1 1 0 0 0 9.73 2.034a10.014 10.014 0 0 0-4.489 2.582c-3.898 3.898-3.898 10.243 0 14.143a9.937 9.937 0 0 0 7.072 2.93 9.93 9.93 0 0 0 7.07-2.929 10.007 10.007 0 0 0 2.583-4.491 1.001 1.001 0 0 0-1.224-1.224zm-2.772 4.301a7.947 7.947 0 0 1-5.656 2.343 7.953 7.953 0 0 1-5.658-2.344c-3.118-3.119-3.118-8.195 0-11.314a7.923 7.923 0 0 1 2.06-1.483 10.027 10.027 0 0 0 2.89 7.848 9.972 9.972 0 0 0 7.848 2.891 8.036 8.036 0 0 1-1.484 2.059z"/></svg></span>
											<span class="light-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M6.993 12c0 2.761 2.246 5.007 5.007 5.007s5.007-2.246 5.007-5.007S14.761 6.993 12 6.993 6.993 9.239 6.993 12zM12 8.993c1.658 0 3.007 1.349 3.007 3.007S13.658 15.007 12 15.007 8.993 13.658 8.993 12 10.342 8.993 12 8.993zM10.998 19h2v3h-2zm0-17h2v3h-2zm-9 9h3v2h-3zm17 0h3v2h-3zM4.219 18.363l2.12-2.122 1.415 1.414-2.12 2.122zM16.24 6.344l2.122-2.122 1.414 1.414-2.122 2.122zM6.342 7.759 4.22 5.637l1.415-1.414 2.12 2.122zm13.434 10.605-1.414 1.414-2.122-2.122 1.414-1.414z"/></svg></span>
										</a>
									</li>


									
									
									
									<li class="nav-item full-screen fullscreen-button">
										<a class="new nav-link full-screen-link" href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24"><path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"/></svg></a>
									</li>
									
									<li class="dropdown main-profile-menu nav nav-item nav-link ps-lg-2">
										<a class="new nav-link profile-user d-flex" href="#" data-bs-toggle="dropdown"><img src="../img/perfil/<?php echo $foto_usu ?>"></a>
										<div class="dropdown-menu">
											<div class="menu-header-content p-3 border-bottom">
												<div class="d-flex wd-100p">
													<div class="main-img-user"><img src="../img/perfil/<?php echo $foto_usu ?>"></div>
													<div class="ms-3 my-auto">
														<h6 class="tx-15 font-weight-semibold mb-0"><?php echo $nome_usu ?></h6><span class="dropdown-title-text subtext op-6  tx-12"><?php echo $nivel_usu ?></span>
													</div>
												</div>
											</div>
											<a class="dropdown-item" href="" data-bs-target="#modalPerfil" data-bs-toggle="modal"><i class="fa fa-user"></i>Perfil</a>	
											<span class="<?php echo $configuracoes ?>"><a class="dropdown-item " href="" data-bs-target="#modalConfig" data-bs-toggle="modal"><i class="fa fa-cogs "></i>  Configurações</a>
											</span>
											
											<a class="dropdown-item" href="logout.php"><i class="fa fa-arrow-left"></i> Sair</a>
										</div>
									</li>
								</ul>
							</div>
						</div>
						
					</div>
				</div>
			</div>				<!-- /APP-HEADER -->

			<!--APP-SIDEBAR-->
			<div class="sticky">
				<aside class="app-sidebar">
					<div class="main-sidebar-header active">
						<a class="header-logo active" href="index.php">
							<img src="../img/foto-painel.png" class="main-logo  desktop-logo" alt="logo">
							<img src="../img/foto-painel.png" class="main-logo  desktop-dark" alt="logo">
							<img src="../img/icone.png" class="main-logo  mobile-logo" alt="logo">
							<img src="../img/icone.png" class="main-logo  mobile-dark" alt="logo">
						</a>
					</div>
					<div class="main-sidemenu">
						<div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"/></svg></div>
						<ul class="side-menu">

							<li class="slide <?php echo @$home ?>">
								<a class="side-menu__item" href="index.php">
									<i class="fa fa-home text-white"></i>
									<span class="side-menu__label" style="margin-left: 15px">Dashboard</span></a>
								</li>


								<li class="slide <?php echo @$menu_pessoas ?>">
									<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
										<i class="fa fa-users text-white mt-1"></i>
										<span class="side-menu__label" style="margin-left: 15px">Pessoas</span><i class="angle fe fe-chevron-right"></i></a>
										<ul class="slide-menu">

											<li class="<?php echo @$usuarios ?>"><a class="slide-item" href="usuarios"> Usuários</a></li>
											
											<li class="<?php echo @$pastores ?>"><a class="slide-item" href="pastores"> Pastores</a></li>
																					
											
										</ul>
									</li>



									<li class="slide <?php echo @$menu_cadastros ?>">
										<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
											<i class="fa fa-plus text-white mt-1"></i>
											<span class="side-menu__label" style="margin-left: 15px">Cadastros</span><i class="angle fe fe-chevron-right"></i></a>
											<ul class="slide-menu">										
												
												
												<li class="<?php echo @$igreja ?>"><a class="slide-item" href="igrejas"> Igrejas</a></li>											

												<li class="<?php echo @$cargos ?> "><a class="slide-item" href="cargos"> Cargos Ministeriais</a></li>


												<?php if($mostrar_acessos == 'Sim'){ ?>

													<li class="<?php echo @$grupo_acessos ?> "><a class="slide-item" href="grupo_acessos"> Grupos</a></li>

													<li class="<?php echo @$acessos ?> "><a class="slide-item" href="acessos"> Acessos</a></li>

												<?php } ?>
												
											</ul>
										</li>



										<li class="slide <?php echo @$menu_consultas ?>">
											<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
												<i class="fa fa-cube text-white mt-1"></i>
												<span class="side-menu__label" style="margin-left: 15px">Consultas</span><i class="angle fe fe-chevron-right"></i></a>
												<ul class="slide-menu">

													<li class="<?php echo @$anexos ?> "><a class="slide-item" href="anexos"> Anexos</a></li>

													<li class="<?php echo @$patrimonios ?> "><a class="slide-item" href="patrimonios"> Patrimônios</a></li>
													
													
												</ul>
											</li>					



											<li class="slide <?php echo @$menu_relatorios ?>">
												<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
													<i class="fa fa-file-text text-white mt-1"></i>
													<span class="side-menu__label" style="margin-left: 15px">Relatórios</span><i class="angle fe fe-chevron-right"></i></a>
													<ul class="slide-menu">


														<li class="<?php echo @$rel_membros ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelMembros"> Membros</a></li>


														<li class="<?php echo @$rel_patrimonios ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelPatrimonios"> Patrimônios</a></li>
														

														<li class="<?php echo @$rel_logs ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelLogs"> Auditoria de Logs</a></li>


														<li class="<?php echo @$rel_transfMembros ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelTransfMembros"> Transferência de Membros</a></li>

														<li class="<?php echo @$rel_fechamentos ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelFechamentos"> Fechamentos Mensais</a></li>

														<li class="<?php echo @$rel_financeiro ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelFin"> Relatório Financeiro</a></li>


														<li class="<?php echo @$rel_sintetico_despesas ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelSinDesp"> Rel Sintético Despesas</a></li>

														<li class="<?php echo @$rel_sintetico_receber ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelSinRec"> Rel Sintético Receber</a></li>


														<li class="<?php echo @$rel_balanco ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelBalanco"> Rel Balanço Anual</a></li>


													</ul>
												</li>

											

													<li class="slide <?php echo @$tarefas ?>">
														<a class="side-menu__item" href="tarefas">
															<i class="fa fa-calendar text-white"></i>
															<span class="side-menu__label" style="margin-left: 15px">Tarefas</span></a>
														</li>




													<li class="slide <?php echo @$site ?>">
													<a class="side-menu__item" href="site">
														<i class="fa fa-globe text-white"></i>
														<span class="side-menu__label" style="margin-left: 15px">Dados Site</span></a>
													</li>




													</ul>
													<div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"><path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"/></svg></div>
												</div>
											</aside>
										</div>				<!--/APP-SIDEBAR-->
									</div>

									<!-- MAIN-CONTENT -->
									<div class="main-content app-content">

										<!-- container -->
										<div class="main-container container-fluid ">

											<?php 
						//Classe para ocultar mobile
											if($ocultar_mobile == 'Sim'){ ?>
												<style type="text/css">
													@media only screen and (max-width: 700px) {
														.ocultar_mobile_app{
															display:none; 
														}
													}
												</style>
											<?php } ?>


											<?php 
											require_once('paginas/'.$pagina.'.php');
											?>				


										</div>
										<!-- Container closed -->
									</div>
									<!-- MAIN-CONTENT CLOSED -->









									<!-- FOOTER -->
									<div class="main-footer">
										<div class="container-fluid pt-0 ht-100p">
											Copyright © <?php echo date('Y'); ?> <a href="javascript:void(0);" class="text-primary">Agência Digital SLZ</a>. Todos os direitos reservados
										</div>
									</div>			<!-- FOOTER END -->

								</div>
								<!-- End Page -->

								<!-- BUYNOW-MODAL -->



								<a href="#top" id="back-to-top"><i class="las la-arrow-up"></i></a>


								<!-- GRAFICOS -->
								<script src="../assets/plugins/chart.js/Chart.bundle.min.js"></script>		
								<script src="../assets/js/apexcharts.js"></script>

								<!--INTERNAL  INDEX JS -->
								<script src="../assets/js/index.js"></script>



								<script src="../assets/plugins/jquery/jquery.min.js"></script>
								<script src="../assets/plugins/bootstrap/js/popper.min.js"></script>
								<script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
								<script src="../assets/plugins/moment/moment.js"></script>
								<script src="../assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
								<script src="../assets/plugins/perfect-scrollbar/p-scroll.js"></script>
								<script src="../assets/js/eva-icons.min.js"></script>
								<script src="../assets/plugins/side-menu/sidemenu.js"></script>
								<script src="../assets/js/sticky.js"></script>
								<script src="../assets/plugins/sidebar/sidebar.js"></script>
								<script src="../assets/plugins/sidebar/sidebar-custom.js"></script>


								<!-- INTERNAL DATA TABLES -->
								<script src="../assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
								<script src="../assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
								<script src="../assets/plugins/datatable/js/dataTables.buttons.min.js"></script>
								<script src="../assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
								<script src="../assets/plugins/datatable/js/jszip.min.js"></script>
								<script src="../assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
								<script src="../assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
								<script src="../assets/plugins/datatable/js/buttons.html5.min.js"></script>
								<script src="../assets/plugins/datatable/js/buttons.print.min.js"></script>
								<script src="../assets/plugins/datatable/js/buttons.colVis.min.js"></script>
								<script src="../assets/plugins/datatable/dataTables.responsive.min.js"></script>
								<script src="../assets/plugins/datatable/responsive.bootstrap5.min.js"></script>


								<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


								<!-- POPOVER JS -->
								<script src="../assets/js/popover.js"></script>

								<script src="../assets/js/index1.js"></script>
								<script src="../assets/js/themecolor.js"></script>
								<script src="../assets/js/custom.js"></script>		





							</body>

							</html>


							<!-- Modal Perfil -->
							<div class="modal fade" id="modalPerfil" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content">
										<div class="modal-header bg-primary text-white">
											<h4 class="modal-title">Alterar Dados</h4>
											<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
										</div>

										<form id="form-perfil">
											<div class="modal-body">


												<div class="row">
													<div class="col-md-6 mb-3">							
														<label>Nome</label>
														<input type="text" class="form-control" id="nome_perfil" name="nome" placeholder="Seu Nome" value="<?php echo @$nome_usu ?>" required>							
													</div>

													<div class="col-md-6 mb-3">							
														<label>Email</label>
														<input type="email" class="form-control" id="email_perfil" name="email" placeholder="Seu Nome" value="<?php echo @$email_usu ?>" required>							
													</div>
												</div>


												<div class="row">
													<div class="col-md-4 mb-3">							
														<label>Telefone</label>
														<input type="text" class="form-control" id="telefone_perfil" name="telefone" placeholder="Seu Telefone" value="<?php echo @$telefone_usu ?>" required>							
													</div>

													<div class="col-md-4 mb-3">							
														<label>Senha</label>
														<input type="password" class="form-control" id="senha_perfil" name="senha" placeholder="Senha" value="<?php echo @$senha_usu ?>" required>							
													</div>

													<div class="col-md-4 mb-3">							
														<label>Confirmar Senha</label>
														<input type="password" class="form-control" id="conf_senha_perfil" name="conf_senha" placeholder="Confirmar Senha" value="" required>							
													</div>


												</div>


												<div class="row">
													<div class="col-md-12 mb-3">	
														<label>CPF</label>
														<input type="text" class="form-control" id="cpf_usu" name="cpf_usu" placeholder="Insira o CPF" value="<?php echo $cpf_usu ?>" >	
													</div>
												</div>



												<div class="row">
													<div class="col-md-8 mb-3">							
														<label>Foto</label>
														<input type="file" class="form-control" id="foto_perfil" name="foto" value="<?php echo @$foto_usu ?>" onchange="carregarImgPerfil()">							
													</div>

													<div class="col-md-4 mb-3">								
														<img src="../img/perfil/<?php echo $foto_usu ?>"  width="80px" id="target-usu">								

													</div>


												</div>


												<input type="hidden" name="id_usuario" value="<?php echo @$id_usuario ?>">


												<br>
												<small><div id="msg-perfil" align="center"></div></small>
											</div>
											<div class="modal-footer">       
												<button type="submit" class="btn btn-primary">Salvar</button>
											</div>
										</form>
									</div>
								</div>
							</div>








							<!-- Modal Config -->
							<div class="modal fade" id="modalConfig" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content">
										<div class="modal-header bg-primary text-white">
											<h4 class="modal-title">Alterar Configurações</h4>
											<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
										</div>
										<form id="form-config">
											<div class="modal-body">


												<div class="row mb-3">
													<div class="col-md-4">							
														<label>Nome Igreja</label>
														<input type="text" class="form-control" id="nome_sistema" name="nome_sistema" placeholder="Delivery Interativo" value="<?php echo @$nome_sistema ?>" required>							
													</div>

													<div class="col-md-4">							
														<label>Email Igreja</label>
														<input type="email" class="form-control" id="email_sistema" name="email_sistema" placeholder="Email do Sistema" value="<?php echo @$email_super_adm ?>" >							
													</div>


													<div class="col-md-4">							
														<label>Telefone Igreja</label>
														<input type="text" class="form-control" id="telefone_sistema" name="telefone_sistema" placeholder="Telefone do Sistema" value="<?php echo @$telefone_igreja_sistema ?>" required>							
													</div>

												</div>


												<div class="row mb-3">
													<div class="col-md-6">							
														<label>Endereço <small>(Rua Número Bairro e Cidade)</small></label>
														<input type="text" class="form-control" id="endereco_sistema" name="endereco_sistema" placeholder="Rua X..." value="<?php echo @$endereco_igreja_sistema ?>" >							
													</div>

													<div class="col-md-3">							
														<label>Instagram</label>
														<input type="text" class="form-control" id="instagram_sistema" name="instagram_sistema" placeholder="Link do Instagram" value="<?php echo @$instagram_sistema ?>">							
													</div>

													<div class="col-md-3">							
														<label>Dias Excluir Logs</label>
														<input type="number" class="form-control" id="dias_excluir_logs" name="dias_excluir_logs" value="<?php echo @$dias_excluir_logs ?>">							
													</div>
												</div>


												<div class="row mb-3">	


													<div class="col-md-3">							
														<label>CNPJ Sistema</label>
														<input type="text" class="form-control" id="cnpj_sistema" name="cnpj_sistema" placeholder="CNPJ" value="<?php echo @$cnpj_sistema ?>">							
													</div>



													<div class="col-md-3">							
														<label>Marca D'agua Rel</label>
														<select name="marca_dagua" class="form-select">
															<option value="Sim" <?php if($marca_dagua == 'Sim'){ ?> selected <?php } ?>>Sim</option>
															<option value="Não" <?php if($marca_dagua == 'Não'){ ?> selected <?php } ?>>Não</option>
														</select>						
													</div>	


													<div class="col-md-3">							
														<label>Qtd Tarefas</label>
														<input type="number" class="form-control" id="qtd_tar_igr" name="qtd_tar_igr" value="<?php echo @$quantidade_tarefas ?>">							
													</div>

													<div class="col-md-3">							
														<label>Limitar Tesoureiro</label>
														<select name="limitar_tesoureiro" class="form-select">
															<option value="Sim" <?php if($limitar_tesoureiro == 'Sim'){ ?> selected <?php } ?>>Sim</option>
															<option value="Não" <?php if($limitar_tesoureiro == 'Não'){ ?> selected <?php } ?>>Não</option>
														</select>						
													</div>

												</div>


												<div class="row mb-3">


													<div class="col-md-3">							
														<label>Relatório PDF</label>
														<select name="relatorio_pdf" class="form-select">
															<option value="Sim" <?php if($relatorio_pdf == 'Sim'){ ?> selected <?php } ?>>Sim</option>
															<option value="Não" <?php if($relatorio_pdf == 'Não'){ ?> selected <?php } ?>>Não</option>
														</select>						
													</div>


													<div class="col-md-3">							
														<label>Cabeçalho Rel Imagem</label>
														<select name="cabecalho_rel_img" class="form-select">
															<option value="Sim" <?php if($cabecalho_rel_img == 'Sim'){ ?> selected <?php } ?>>Sim</option>
															<option value="Não" <?php if($cabecalho_rel_img == 'Não'){ ?> selected <?php } ?>>Não</option>
														</select>						
													</div>


													<div class="col-md-3">							
														<label>Itens Por Página (Site)</label>
														<input type="number" class="form-control" id="itens_por_pagina" name="itens_por_pagina" value="<?php echo @$itens_por_pagina ?>">							
													</div>


													<div class="col-md-3">							
														<label>Auditoria (logs)</label>
														<select name="logs" class="form-select">
															<option value="Sim" <?php if($logs == 'Sim'){ ?> selected <?php } ?>>Sim</option>
															<option value="Não" <?php if($logs == 'Não'){ ?> selected <?php } ?>>Não</option>
														</select>						
													</div>

												</div>


												<div class="row mb-3">

													<div class="col-md-3">							
														<label>Assinatura Recibo</label>
														<select name="assinatura_recibo" class="form-select">
															<option value="Sim" <?php if($assinatura_recibo == 'Sim'){ ?> selected <?php } ?>>Sim</option>
															<option value="Não" <?php if($assinatura_recibo == 'Não'){ ?> selected <?php } ?>>Não</option>
														</select>						
													</div>

													<div class="col-md-3">							
														<label>Impressão Automática</label>
														<select name="impressao_automatica" class="form-select">
															<option value="Sim" <?php if($impressao_automatica == 'Sim'){ ?> selected <?php } ?>>Sim</option>
															<option value="Não" <?php if($impressao_automatica == 'Não'){ ?> selected <?php } ?>>Não</option>
														</select>						
													</div>


													<div class="col-md-3">							
														<label>Entrar Automáticamente</label>
														<select name="entrar_automatico" class="form-select">
															<option value="Sim" <?php if($entrar_automatico == 'Sim'){ ?> selected <?php } ?>>Sim</option>
															<option value="Não" <?php if($entrar_automatico == 'Não'){ ?> selected <?php } ?>>Não</option>
														</select>						
													</div>

													<div class="col-md-3">							
														<label>Mostrar PreLoader</label>
														<select name="mostrar_preloader" class="form-select">
															<option value="Sim" <?php if($mostrar_preloader == 'Sim'){ ?> selected <?php } ?>>Sim</option>
															<option value="Não" <?php if($mostrar_preloader == 'Não'){ ?> selected <?php } ?>>Não</option>
														</select>						
													</div>
												</div>


												<div class="row mb-3">

													<div class="col-md-3">							
														<label>
															<div class="icones_mobile" class="dropdown" style="display: inline-block; ">                      
																<a title="Clique para ver as opções das apis" href="#" aria-expanded="false" aria-haspopup="true" data-bs-toggle="dropdown" class="dropdown"><i class="fa fa-info-circle text-primary"></i> </a>
																<div  class="dropdown-menu tx-13" style="width:500px">
																	<div class="dropdown-item-text botao_excluir" style="width:500px">
																		<p>Integrei ao projeto 3 serviços de apis, estes serviços sáo terceirizados e tem custo adicional para utilização, segue abaixo site e contato dos 3. <br><br>

																			<b>1 - (Menuia)</b> Diego (81)98976-9960 <br>
																			<a href="https://chatbot.menuia.com/" target="_blank">https://chatbot.menuia.com/ </a> 
																			<br><br>

																			<b>2 - (WordMensagens)</b> Bruno (44) 99986-3238 <br>
																			<a href="http://wordmensagens.com.br/sistema/index.php" target="_blank">http://wordmensagens.com.br/sistema/index.php </a>
																			<br><br>

																			<b>3 - (NewTek)</b> Nando Silva (21) 99998-8666 <br>
																			<a href="https://webapp.newteksoft.com.br/" target="_blank">https://webapp.newteksoft.com.br/ </a>
																			<br><br>

																		</p>

																	</div>
																</div>
															</div>

															Api Whatsapp <a href="#" onclick="testarApi()" title="Testar disparo Api"><i class="fa fa-whatsapp text-verde"></i></a></label>
															<select name="api_whatsapp" id="api_whatsapp" class="form-select">
																<option value="Não" <?php if($api_whatsapp == 'Não'){ ?> selected <?php } ?>>Não</option>
																<option value="menuia" <?php if($api_whatsapp == 'menuia'){ ?> selected <?php } ?>>Menuia</option>
																<option value="wm" <?php if($api_whatsapp == 'wm'){ ?> selected <?php } ?>>WordMensagens</option>
																<option value="newtek" <?php if($api_whatsapp == 'newtek'){ ?> selected <?php } ?>>NewTek</option>


															</select>						
														</div>


														<div class="col-md-4">							
															<label>Token Whatsapp (appkey)</label>
															<input type="text" class="form-control" id="token_whatsapp" name="token_whatsapp" placeholder="Token ou App Key" value="<?php echo @$token_whatsapp ?>">							
														</div>


														<div class="col-md-5">							
															<label>Instância Whatsapp (authKey)</label>
															<input type="text" class="form-control" id="instancia_whatsapp" name="instancia_whatsapp" placeholder="Instância ou authKey" value="<?php echo @$instancia_whatsapp ?>">							
														</div>


													</div>






													<div class="row mb-3">

														<div class="col-md-3">							
															<label>Ocultar Itens Mobile</label>
															<select name="ocultar_mobile" class="form-select">
																<option value="Sim" <?php if($ocultar_mobile == 'Sim'){ ?> selected <?php } ?>>Sim</option>
																<option value="Não" <?php if($ocultar_mobile == 'Não'){ ?> selected <?php } ?>>Não</option>
															</select>						
														</div>



														<div class="col-md-3">							
															<label>Alterar Acessos</label>
															<select name="alterar_acessos" class="form-select">
																<option value="Sim" <?php if($alterar_acessos == 'Sim'){ ?> selected <?php } ?>>Sim</option>
																<option value="Não" <?php if($alterar_acessos == 'Não'){ ?> selected <?php } ?>>Não</option>
															</select>						
														</div>


														<div class="col-md-3">							
															<label>Dados para Pagamentos</label>
															<input type="text" class="form-control" id="dados_pagamento" name="dados_pagamento" placeholder="Vai aparecer nas cobranças se preenchido" value="<?php echo @$dados_pagamento ?>">			
														</div>

														<div class="col-md-3">							
															<label>Mostrar Acessos</label>
															<select name="mostrar_acessos" class="form-select">
																<option value="Sim" <?php if($mostrar_acessos == 'Sim'){ ?> selected <?php } ?>>Sim</option>
																<option value="Não" <?php if($mostrar_acessos == 'Não'){ ?> selected <?php } ?>>Não</option>
															</select>						
														</div>





													</div>

													<div class="row mb-3">

														<div class="col-md-6">							
															<label>Multa Atraso Conta</label>
															<input type="text" class="form-control" id="multa_atraso" name="multa_atraso" placeholder="Valor em R$" value="<?php echo @$multa_atraso ?>">							
														</div>	

														<div class="col-md-6">							
															<label>Júros Atraso Dia Conta</label>
															<input type="text" class="form-control" id="juros_atraso" name="juros_atraso" placeholder="Valor em %" value="<?php echo @$juros_atraso ?>">							
														</div>	

													</div>



													<div class="row mb-3">
														<div class="col-md-4">						
															<div class="form-group"> 
																<label>Logo (*PNG)</label> 
																<input class="form-control" type="file" name="foto-logo" onChange="carregarImgLogo();" id="foto-logo" >
															</div>						
														</div>
														<div class="col-md-2">
															<div id="divImg">
																<img src="../teste/<?php echo @$logo_sistema ?>"  width="80px" id="target-logo">									
															</div>
														</div>


														<div class="col-md-4">						
															<div class="form-group"> 
																<label>Ícone (*Png)</label> 
																<input class="form-control" type="file" name="foto-icone" onChange="carregarImgIcone();" id="foto-icone">
															</div>						
														</div>
														<div class="col-md-2">
															<div id="divImg">
																<img src="../img/<?php echo @$icone_sistema ?>"  width="50px" id="target-icone">									
															</div>
														</div>


													</div>




													<div class="row mb-3">
														<div class="col-md-4">						
															<div class="form-group"> 
																<label>Logo Relatório (*Jpg)</label> 
																<input class="form-control" type="file" name="foto-logo-rel" onChange="carregarImgLogoRel();" id="foto-logo-rel">
															</div>						
														</div>
														<div class="col-md-2">
															<div id="divImg">
																<img src="../img/<?php echo @$logo_rel ?>"  width="80px" id="target-logo-rel">									
															</div>
														</div>




														<div class="col-md-4">						
															<div class="form-group"> 
																<label>Logo Painél (Clara)(*PNG)</label> 
																<input class="form-control" type="file" name="foto-painel" onChange="carregarImgPainel();" id="foto-painel">
															</div>						
														</div>
														<div class="col-md-2">
															<div id="divImg">
																<img src="../img/foto-painel.png"  width="80px" id="target-painel">									
															</div>
														</div>


														<div class="col-md-4">						
															<div class="form-group"> 
																<label>Assinatura (*Jpg)</label> 
																<input class="form-control" type="file" name="assinatura_rel" onChange="carregarImgAssinatura();" id="assinatura_rel">
															</div>						
														</div>
														<div class="col-md-2">
															<div id="divImg">
																<img src="../img/assinatura.jpg"  width="80px" id="target-assinatura">									
															</div>
														</div>



													</div>					


													<br>
													<small><div id="msg-config" align="center"></div></small>
												</div>
												<div class="modal-footer">       
													<button type="submit" class="btn btn-primary">Salvar</button>
												</div>
											</form>
										</div>
									</div>
								</div>






								<div class="modal fade" id="modalRelFechamentos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="exampleModalLabel"><small>Relatório de Fechamentos</small></h4>
												<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
											</div>
											<form method="post" action="rel/rel_fechamentos_class.php" target="_blank">
												<div class="modal-body">
													<div class="row">					


														<div class="col-md-12">
															<div class="mb-3">
																<label for="exampleFormControlInput1" class="form-label">Data do Mês Fechamento </label>
																<input type="date" class="form-control form-control-sm" name="dataInicial" value="<?php echo date('Y-m-d') ?>">
															</div>
														</div>




													</div>


												</div>

												<small><div align="center" id="msg-transf"></div></small>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-transf">Fechar</button>
													<button type="submit" class="btn btn-primary">Gerar Relatório</button>
												</div>
											</form>
										</div>
									</div>
								</div>






								<div class="modal fade" id="modalRelLogs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Relatório de Logs</h5>
												<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
											</div>
											<form method="post" action="rel/rel_logs_class.php" target="_blank">
												<div class="modal-body">
													<div class="row">


														<div class="col-md-3">
															<div class="mb-3">
																<label >Ações</label>
																<select class="form-select form-select-sm" aria-label="Default select example" name="acao" id="acao">  
																	<option value="">Todas</option>
																	<option value="Exclusão">Exclusão</option>
																	<option value="Inserção">Inserção</option>
																	<option value="Edição">Edição</option>
																	<option value="Login">Login</option>
																	<option value="Logout">Logout</option>
																</select>
															</div>
														</div>


														<div class="col-md-3">
															<div class="mb-3">
																<label >Tabelas</label>
																<select class="form-select form-select-sm" aria-label="Default select example" name="tabelas" id="tabelas">  
																	<option value="">Todas</option>
																	<option value="usuarios">Usuários</option>
																	<option value="patrimonios">Patrimônios</option>
																	<option value="tesoureiros">Tesoureiros</option>
																	<option value="secretarios">Secretários</option>
																	<option value="pastores">Pastores</option>
																	<option value="igrejas">Igrejas</option>
																	<option value="frequencias">Frequencias</option>
																	<option value="cargos">Cargos</option>
																	<option value="bispos">Bispos</option>
																	<option value="anexos">Anexos</option>
																</select>
															</div>
														</div>



														<div class="col-md-3">
															<div class="mb-3">
																<label >Data Inicial </label>
																<input type="date" class="form-control form-control-sm" name="dataInicial" id="dtInicial-log" value="<?php echo $data_atual ?>">
															</div>
														</div>

														<div class="col-md-3">
															<div class="mb-3">
																<label >Data Final</label>
																<input type="date" class="form-control form-control-sm"  name="dataFinal" id="dtFinal-log" value="<?php echo $data_atual ?>">
															</div>
														</div>


													</div>


												</div>

												<small><div align="center" id="msg-config"></div></small>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-config">Fechar</button>
													<button type="submit" class="btn btn-primary">Gerar Relatório</button>
												</div>
											</form>
										</div>
									</div>
								</div>





								<div class="modal fade" id="modalRelTransfMembros" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title" id="exampleModalLabel"><small>Relatório de Transferência</small></h4>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<form method="post" action="rel/rel_transferencia_class.php" target="_blank">
												<div class="modal-body">
													<div class="row">




														<div class="col-md-6">
															<div class="mb-3">
																<label >Data Inicial </label>
																<input type="date" class="form-control form-control-sm" name="dataInicial" id="dtInicial-tra" value="1980-01-01">
															</div>
														</div>

														<div class="col-md-6">
															<div class="mb-3">
																<label >Data Final</label>
																<input type="date" class="form-control form-control-sm"  name="dataFinal" id="dtFinal-tra" value="<?php echo $data_atual ?>">
															</div>
														</div>


													</div>


												</div>

												<small><div align="center" id="msg-transf"></div></small>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-transf">Fechar</button>
													<button type="submit" class="btn btn-primary">Gerar Relatório</button>
												</div>
											</form>
										</div>
									</div>
								</div>




								<div class="modal fade" id="modalRelPatrimonios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Relatório de Patrimônios</h5>
												<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
											</div>
											<form method="post" action="../painel-igreja/rel/rel_patrimonio_class.php" target="_blank">
												<div class="modal-body">
													<div class="row">

														<div class="col-md-3">
															<div class="mb-3">
																<label >Igreja</label>
																<select class="form-select" name="igreja" style="width:100%;" required>
																	<option value="0">Todas as Igrejas</option>
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
															</div>



															<div class="col-md-3">
																<div class="mb-3">
																	<label >Itens</label>
																	<select class="form-select form-select-sm" aria-label="Default select example" name="itens">  
																		<option value="">Todos</option>
																		<option value="1">Pertencentes a Igreja</option>
																		<option value="2">Emprestados a Outros</option>
																		<option value="3">Itens de Outras Igrejas</option>
																	</select>
																</div>
															</div>

															<div class="col-md-3">
																<div class="mb-3">
																	<label >Status</label>
																	<select class="form-select form-select-sm" aria-label="Default select example" name="status">  
																		<option value="">Todos</option>
																		<option value="Sim">Ativo</option>
																		<option value="Não">Inativo</option>
																	</select>
																</div>
															</div>

															<div class="col-md-3">
																<div class="mb-3">
																	<label >Compra / Doação</label>
																	<select class="form-select form-select-sm" aria-label="Default select example" name="entrada">  
																		<option value="">Todos</option>
																		<option value="Compra">Compra</option>
																		<option value="Doação">Doação</option>
																	</select>
																</div>
															</div>


														</div>

														<div class="row">
															<div class="col-md-4">
																<div class="mb-3">
																	<label >Data Inicial</label>
																	<input type="date" class="form-control form-control-sm" name="dataInicial" id="dtInicial-pat" value="1980-01-01">
																</div>
															</div>

															<div class="col-md-4">
																<div class="mb-3">
																	<label >Data Final</label>
																	<input type="date" class="form-control form-control-sm"  name="dataFinal" id="dtFinal-pat" value="<?php echo $data_atual ?>">
																</div>
															</div>


														</div>


													</div>

													<small><div align="center" id="msg-config"></div></small>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-config">Fechar</button>
														<button type="submit" class="btn btn-primary">Gerar Relatório</button>
													</div>
												</form>
											</div>
										</div>
									</div>

									<!-- Modal Rel Financeiro -->
									<div class="modal fade" id="modalRelFin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">	
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header bg-primary text-white">
													<h4 class="modal-title" id="exampleModalLabel">Relatório Financeiro</h4>
													<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
												</div>
												<form method="POST" action="../painel-igreja/rel/financeiro_class.php" target="_blank">
													<div class="modal-body">	
														<div class="row">
															<div class="col-md-3">
															<div class="mb-3">
																<label >Igreja</label>
																<select class="form-select" name="igreja" style="width:100%;" required>
																	<option value="0">Todas as Igrejas</option>
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
															</div>
															<div class="col-md-3">
																<label>Data Inicial</label>
																<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
															</div>

															<div class="col-md-3">
																<label>Data Final</label>
																<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
															</div>

															<div class="col-md-3">
																<label>Filtro Data</label>
																<select name="filtro_data" class="form-select">
																	<option value="data_lanc">Data de Lançamento</option>
																	<option value="vencimento">Data de Vencimento</option>
																	<option value="data_pgto">Data de Pagamento</option>
																</select>
															</div>
														</div>		


														<div class="row">				
															<div class="col-md-4">
																<label>Entradas / Saídas</label>
																<select name="filtro_tipo" class="form-select">
																	<option value="receber">Entradas / Ganhos</option>
																	<option value="pagar">Saídas / Despesas</option>
																</select>
															</div>

															<div class="col-md-4">
																<label>Tipo Lançamento</label>
																<select name="filtro_lancamento" class="form-select">
																	<option value="">Tudo</option>
																	<option value="Conta">Ganhos / Despesas</option>
																	<option value="Dízimo">Dízimos</option>
																	<option value="Oferta">Ofertas</option>
																	<option value="Missões Recebidas">Ofertas Missões Recebidas</option>
																	<option value="Doações">Doações</option>
																	<option value="Vendas">Vendas</option>
																	<option value="Missões Enviadas">Ofertas Missões Enviadas</option>

																</select>
															</div>
															<div class="col-md-4">
																<label>Pendentes / Pago</label>
																<select name="filtro_pendentes" class="form-select">
																	<option value="">Tudo</option>
																	<option value="Não">Pendentes</option>
																	<option value="Sim">Pago</option>
																</select>
															</div>			
														</div>		



													</div>
													<div class="modal-footer">       
														<button type="submit" class="btn btn-primary">Gerar</button>
													</div>
												</form>
											</div>
										</div>
									</div>




									<div class="modal fade" id="modalRelMembros" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Relatório de Membros</h5>
													<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
												</div>
												<form method="post" action="../painel-igreja/rel/rel_membros_class.php" target="_blank">
													<div class="modal-body">
														<div class="row">

															<div class="col-md-4">
																<div class="mb-3">
																	<label >Igreja</label>
																	<select class="form-select" name="igreja" style="width:100%;" required>
																		<option value="0">Todas as Igrejas</option>
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
																</div>



																<div class="col-md-4">
																	<div class="mb-3">
																		<label>Cargo / Membros</label>
																		<select class="form-select form-select-sm" aria-label="Default select example" name="cargo">  
																			<option value="">Todos</option>

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


																	<div class="col-md-4">
																		<div class="mb-3">
																			<label>Status</label>
																			<select class="form-select form-select-sm" aria-label="Default select example" name="status">  
																				<option value="">Todos</option>
																				<option value="Sim">Ativo</option>
																				<option value="Não">Inativo</option>
																			</select>
																		</div>
																	</div>

																	<div class="col-md-4">

																	</div>


																</div>


															</div>

															<small><div align="center" id="msg-config"></div></small>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-fechar-config">Fechar</button>
																<button type="submit" class="btn btn-primary">Gerar Relatório</button>
															</div>
														</form>
													</div>
												</div>
											</div>






											<!-- Modal Rel Sintético Despesas -->
											<div class="modal fade" id="modalRelSinDesp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header bg-primary text-white">
															<h4 class="modal-title" id="exampleModalLabel">Relatório Sintético Contas à Pagar</h4>
															<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
														</div>
														<form method="POST" action="../painel-igreja/rel/sintetico_class.php" target="_blank">
															<div class="modal-body">	
																<div class="row">

																	<div class="col-md-4">
															<div class="mb-3">
																<label >Igreja</label>
																<select class="form-select" name="igreja" style="width:100%;" required>
																	<option value="0">Todas as Igrejas</option>
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
															</div>

																	<div class="col-md-4">
																		<label>Data Inicial</label>
																		<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
																	</div>

																	<div class="col-md-4">
																		<label>Data Final</label>
																		<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
																	</div>

																</div>		


																<div class="row">	


																	<div class="col-md-4">
																		<label>Filtro Data</label>
																		<select name="filtro_data" class="form-select">
																			<option value="data_lanc">Data de Lançamento</option>
																			<option value="vencimento">Data de Vencimento</option>
																			<option value="data_pgto">Data de Pagamento</option>
																		</select>
																	</div>		


																	<div class="col-md-4">
																		<label>Tipo Filtro Contas</label>
																		<select name="filtro_lancamento" class="form-select">					
																			<option value="fornecedor">Fornecedores</option>
																			<option value="pastores">Pastores</option>
																			<option value="secretarios">Secretários</option>

																		</select>
																	</div>
																	<div class="col-md-4">
																		<label>Pendentes / Pago</label>
																		<select name="filtro_pendentes" class="form-select">
																			<option value="">Tudo</option>
																			<option value="Não">Pendentes</option>
																			<option value="Sim">Pago</option>
																		</select>
																	</div>			
																</div>		



															</div>
															<div class="modal-footer">       
																<button type="submit" class="btn btn-primary">Gerar</button>
															</div>
														</form>
													</div>
												</div>
											</div>







											<!-- Modal Rel Sintético Recebimentos -->
											<div class="modal fade" id="modalRelSinRec" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header bg-primary text-white">
															<h4 class="modal-title" id="exampleModalLabel">Relatório Sintético Recebimentos</h4>
															<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
														</div>
														<form method="POST" action="../painel-igreja/rel/sintetico_recebimentos_class.php" target="_blank">
															<div class="modal-body">	
																<div class="row">

																	<div class="col-md-4">
															<div class="mb-3">
																<label >Igreja</label>
																<select class="form-select" name="igreja" style="width:100%;" required>
																	<option value="0">Todas as Igrejas</option>
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
															</div>


																	<div class="col-md-4">
																		<label>Data Inicial</label>
																		<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
																	</div>

																	<div class="col-md-4">
																		<label>Data Final</label>
																		<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
																	</div>

																	
																</div>		


																<div class="row">	

																<div class="col-md-4">
																		<label>Filtro Data</label>
																		<select name="filtro_data" class="form-select">
																			<option value="data_lanc">Data de Lançamento</option>
																			<option value="vencimento">Data de Vencimento</option>
																			<option value="data_pgto">Data de Pagamento</option>
																		</select>
																	</div>		


																	<div class="col-md-4">
																		<label>Tipo Filtro Contas</label>
																		<select name="filtro_lancamento" class="form-select">					
																			<option value="membro">Membros</option>						

																		</select>
																	</div>
																	<div class="col-md-4">
																		<label>Pendentes / Pago</label>
																		<select name="filtro_pendentes" class="form-select">
																			<option value="">Tudo</option>
																			<option value="Não">Pendentes</option>
																			<option value="Sim">Pago</option>
																		</select>
																	</div>			
																</div>		



															</div>
															<div class="modal-footer">       
																<button type="submit" class="btn btn-primary">Gerar</button>
															</div>
														</form>
													</div>
												</div>
											</div>








											<!-- Modal Rel Sintético Recebimentos -->
											<div class="modal fade" id="modalRelBalanco" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header bg-primary text-white">
															<h4 class="modal-title" id="exampleModalLabel">Relatório Balanço Anual</h4>
															<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
														</div>
														<form method="POST" action="../painel-igreja/rel/balanco_anual_class.php" target="_blank">
															<div class="modal-body">	
																<div class="row">

																	<div class="col-md-12">
															<div class="mb-3">
																<label >Igreja</label>
																<select class="form-select" name="igreja" style="width:100%;" required>
																	<option value="0">Todas as Igrejas</option>
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
															</div>


																	
																</div>		


																


															</div>
															<div class="modal-footer">       
																<button type="submit" class="btn btn-primary">Gerar</button>
															</div>
														</form>
													</div>
												</div>
											</div>







											<!-- SweetAlert JS -->
											<script src="js/sweetalert2.all.min.js"></script>
											<link rel="stylesheet" href="js/sweetalert1.min.css" />
											<script src="js/alertas.js"></script>





											<!-- Mascaras JS -->
											<script type="text/javascript" src="js/mascaras.js"></script>

											<!-- Ajax para funcionar Mascaras JS -->
											<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 





											<script type="text/javascript">
												function carregarImgPerfil() {
													var target = document.getElementById('target-usu');
													var file = document.querySelector("#foto_perfil").files[0];

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
												$("#form-perfil").submit(function () {

													event.preventDefault();
													var formData = new FormData(this);

													$.ajax({
														url: "editar-perfil.php",
														type: 'POST',
														data: formData,

														success: function (mensagem) {
															$('#msg-perfil').text('');
															$('#msg-perfil').removeClass()
															if (mensagem.trim() == "Editado com Sucesso") {

																$('#btn-fechar-perfil').click();
																location.reload();				


															} else {

																$('#msg-perfil').addClass('text-danger')
																$('#msg-perfil').text(mensagem)
															}


														},

														cache: false,
														contentType: false,
														processData: false,

													});

												});
											</script>






											<script type="text/javascript">
												$("#form-config").submit(function () {

													event.preventDefault();
													var formData = new FormData(this);

													$.ajax({
														url: "editar-config.php",
														type: 'POST',
														data: formData,

														success: function (mensagem) {
															$('#msg-config').text('');
															$('#msg-config').removeClass()
															if (mensagem.trim() == "Editado com Sucesso") {

																$('#btn-fechar-config').click();
																location.reload();				


															} else {

																$('#msg-config').addClass('text-danger')
																$('#msg-config').text(mensagem)
															}


														},

														cache: false,
														contentType: false,
														processData: false,

													});

												});
											</script>




											<script type="text/javascript">
												function carregarImgLogo() {
													var target = document.getElementById('target-logo');
													var file = document.querySelector("#foto-logo").files[0];

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
												function carregarImgLogoRel() {
													var target = document.getElementById('target-logo-rel');
													var file = document.querySelector("#foto-logo-rel").files[0];

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
												function carregarImgIcone() {
													var target = document.getElementById('target-icone');
													var file = document.querySelector("#foto-icone").files[0];

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
												function carregarImgAssinatura() {
													var target = document.getElementById('target-assinatura');
													var file = document.querySelector("#assinatura_rel").files[0];

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
												function carregarImgPainel() {
													var target = document.getElementById('target-painel');
													var file = document.querySelector("#foto-painel").files[0];

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
												function testarApi(){
													var seletor_api = $('#api_whatsapp').val();
													var token = $('#token_whatsapp').val();
													var instancia = $('#instancia_whatsapp').val();
													var telefone_sistema = $('#telefone_sistema').val();

													$.ajax({
														url: 'apis/teste_whatsapp.php',
														method: 'POST',
														data: {seletor_api, token, instancia, telefone_sistema},
														dataType: "html",

														success:function(result){
															alert(result)
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