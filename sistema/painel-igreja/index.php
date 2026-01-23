<?php 
@session_start();
require_once("../conexao.php");
require_once("verificar.php");

$id_usuario = @$_SESSION['id_usuario'];
$nivel_usu = @$_SESSION['nivel_usuario'];

if($nivel_usu == 'Tesoureiro'){
	$esc_tesoureiro = 'd-none';
	
}else{
	$esc_tesoureiro = '';
	
}

if($nivel_usu == 'Secretario'){
	$esc_secretario = 'd-none';
	
}else{
	$esc_secretario = '';
	
}

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


if(@$_GET['igreja'] > 0){
	@$_SESSION['id_igreja'] = @$_GET['igreja'];
}

$id_igreja = @$_SESSION['id_igreja'];

//TRAZER OS DADOS DA IGREJA
$query = $pdo->query("SELECT * FROM igrejas where id = '$id_igreja'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_igreja = $res[0]['nome'];
$foto_igreja = $res[0]['imagem'];
$foto_painel = $res[0]['painel_jpg'];
$url_do_site = $res[0]['url'];
$pag_inicial = 'home';


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
	$mostrar_registros = $res[0]['mostrar_registros'];
	$igreja_usuario = $res[0]['igreja'];
}else{
	echo '<script>window.location="../"</script>';
	exit();
}


if(@$_SESSION['nivel'] != 'Pastor' and @$_SESSION['nivel'] != 'Bispo' and $igreja_usuario != 0){
	require_once("verificar_permissoes.php");
}

// Ajuste de dashboard para nível Midia
if ($nivel_usu == 'Midia' && (empty($_GET['pagina']) || !isset($_GET['pagina']))) {
    $pagina = 'home_midia';
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
								<img src="../img/igrejas/<?php echo $foto_painel ?>" class="mobile-logo logo-1" alt="logo" style="width:40% !important; margin-left: -120px !important">
								<img src="../img/igrejas/<?php echo $foto_painel ?>" class="mobile-logo dark-logo-1" alt="logo" style="width:40% !important; margin-left: -120px !important">
							</a>
						</div>
						<div class="app-sidebar__toggle" data-bs-toggle="sidebar">
							<a class="open-toggle" href="javascript:void(0);"><i class="header-icon fe fe-align-left" ></i></a>
							<a class="close-toggle" href="javascript:void(0);"><i class="header-icon fe fe-x"></i></a>
						</div>
						<div class="logo-horizontal">
							<a href="index.php" class="header-logo">
								<img src="../img/igrejas/<?php echo $foto_painel ?>" class="mobile-logo logo-1" alt="logo">
								<img src="../img/igrejas/<?php echo $foto_painel ?>" class="mobile-logo dark-logo-1" alt="logo">
							</a>
						</div>

						<div class="main-header-center ms-4 d-sm-none d-md-none d-lg-block form-group">
							<div style="margin-top: 10px; margin-left: 10px;color: #ffff; font-size: 15px;">
								<p class="mb-1" style="display:inline-block;"><?php echo $nome_igreja ?></p>

								<p style="display:inline-block;">
								<?php if($igreja_usuario == 0){?>

												<a class="dropdown-item text-info" href="../painel-admin"><i class="fa fa-arrow-left"></i>Painel Administrador</a>
											<?php } ?>
								</p>


							</div>
						</div>


						<a href="index.php">

						</a>
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


									<?php 
									$dataMes = Date('m');
									$dataDia = Date('d');

									$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia' order by data_nasc asc, id desc");

									$query_pastores = $pdo->query("SELECT * FROM pastores where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia' order by data_nasc asc, id desc");

									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_reg = count($res);
									$res_pastores = $query_pastores->fetchAll(PDO::FETCH_ASSOC);
									$total_reg_pastores = count($res_pastores);
									$total_aniv = $total_reg + $total_reg_pastores;
	
								
									?>



									<li class="dropdown nav-item  main-header-message ">
										<a class="new nav-link"  data-bs-toggle="dropdown" href="javascript:void(0);">
											<small><i class="fa fa-birthday-cake" style="color:#fad8a5;"></i></small>
											<span class="badge  header-badge" style="background:#4a5d96"><?php echo $total_aniv ?></span>
										</a>

										

										<div class="dropdown-menu">
											<div class="menu-header-content text-start border-bottom">
												<div class="d-flex">
													<h6 class="dropdown-title mb-1 tx-15 font-weight-semibold">Aniversariantes Hoje</h6>
													
												</div>
												<p class="dropdown-title-text subtext mb-0 op-6 pb-0 tx-12 "><?php echo $total_aniv ?> Aniversariante(s) Hoje</p>
											</div>
											<div class="main-message-list chat-scroll">

												<?php 
												
												if($total_aniv > 0){
		
	for($i=0; $i<$total_aniv; $i++){
					$nome = $res[$i]['nome'];
					$cpf = $res[$i]['cpf'];
					$email = $res[$i]['email'];
					$telefone = $res[$i]['telefone'];
					$endereco = $res[$i]['endereco'];
					$foto = $res[$i]['imagem'];
					$data_nasc = $res[$i]['data_nasc'];
					$data_cad = $res[$i]['data_cad'];
					$obs = $res[$i]['obs'];
					$igreja = $res[$i]['igreja'];
					$cargo = $res[$i]['cargo'];
					$data_bat = $res[$i]['data_bat'];
					$igreja = $res[$i]['igreja'];
					$ativo = $res[$i]['ativo'];
					$id = $res[$i]['id'];
					$estado = $res[$i]['estado_civil'];

		

		$query_con2 = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
		$res_con2 = $query_con2->fetchAll(PDO::FETCH_ASSOC);
		if(count($res_con2) > 0){
			$nome_cargo = $res_con2[0]['nome'];
		}else{
			$nome_cargo = '';
		}	
			
													?>

													<a href="aniversariantes" class="dropdown-item d-flex border-bottom">
														
														<div class="wd-90p">
															<div class="d-flex">
																<h5 class="mb-0 name" style="color:green"><?php echo $nome ?></h5>
															</div>
															<p class="mb-0 desc"><?php echo $nome_cargo ?> </p>
															
														</div>
													</a>

												<?php } } ?>
												
												
											</div>
											<div class="text-center dropdown-footer">
												<a class="btn btn-primary btn-sm btn-block text-center"  href="aniversariantes">Ver Todos</a>
											</div>
										</div>
									</li>


									<?php 
												$query = $pdo->query("SELECT * from receber where vencimento < curDate() and pago != 'Sim' and igreja = '$id_igreja' order by id asc");
												$res = $query->fetchAll(PDO::FETCH_ASSOC);
												$linhas = @count($res);
												?>
									<li class="dropdown nav-item  main-header-message ">
										<a class="new nav-link"  data-bs-toggle="dropdown" href="javascript:void(0);">
											<small><i class="fa fa-dollar" style="color:#d4fae1;"></i></small>
											<span class="badge  header-badge" style="background:green"><?php echo $linhas ?></span>
										</a>

										

										<div class="dropdown-menu">
											<div class="menu-header-content text-start border-bottom">
												<div class="d-flex">
													<h6 class="dropdown-title mb-1 tx-15 font-weight-semibold">Contas à Receber</h6>


													
												</div>
												<p class="dropdown-title-text subtext mb-0 op-6 pb-0 tx-12 "><?php echo $linhas ?> Contas Vencidas!</p>
											</div>
											<div class="main-message-list chat-scroll">

												<?php 
												$query = $pdo->query("SELECT * from receber where vencimento < curDate() and pago != 'Sim' and igreja = '$id_igreja' order by id asc");
												$res = $query->fetchAll(PDO::FETCH_ASSOC);
												$linhas = @count($res);
												for($i=0; $i<$linhas; $i++){
													$valor = $res[$i]['valor'];
													$vencimento = $res[$i]['vencimento'];
													$valorF = @number_format($valor, 2, ',', '.');	
													$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));									
													?>

													<a href="receber" class="dropdown-item d-flex border-bottom">
														
														<div class="wd-90p">
															<div class="d-flex">
																<h5 class="mb-0 name" style="color:green">R$ <?php echo $valorF ?></h5>
															</div>
															<p class="mb-0 desc"><?php echo $res[$i]['descricao'] ?> </p>
															<p class="time mb-0 text-start float-start ms-2"><?php echo $vencimentoF ?> </p>
														</div>
													</a>

												<?php } ?>
												
												
											</div>
											<div class="text-center dropdown-footer">
												<a class="btn btn-primary btn-sm btn-block text-center"  href="receber">Ver Todas</a>
											</div>
										</div>
									</li>




									<?php 
									$query = $pdo->query("SELECT * from pagar where vencimento < curDate() and pago != 'Sim' and igreja = '$id_igreja' order by id asc");
									$res = $query->fetchAll(PDO::FETCH_ASSOC);
									$linhas = @count($res);
									?>

									<li class="dropdown nav-item  main-header-message ">
										<a class="new nav-link"  data-bs-toggle="dropdown" href="javascript:void(0);">
											<small><i class="fa fa-dollar" style="color:#fff3f2;"></i></small>
											<span class="badge  header-badge" style="background:red"><?php echo $linhas ?></span>
										</a>

										

										<div class="dropdown-menu">
											<div class="menu-header-content text-start border-bottom">
												<div class="d-flex">
													<h6 class="dropdown-title mb-1 tx-15 font-weight-semibold">Contas à Pagar</h6>
													
												</div>
												<p class="dropdown-title-text subtext mb-0 op-6 pb-0 tx-12 "><?php echo $linhas ?> Contas Vencidas!</p>
											</div>
											
											<div class="main-message-list Notification-scroll">

												<?php 
												$query = $pdo->query("SELECT * from pagar where vencimento < curDate() and pago != 'Sim' and igreja = '$id_igreja' order by id asc");
												$res = $query->fetchAll(PDO::FETCH_ASSOC);
												$linhas = @count($res);
												for($i=0; $i<$linhas; $i++){
													$valor = $res[$i]['valor'];
													$valorF = @number_format($valor, 2, ',', '.');
													$vencimentoF = implode('/', array_reverse(@explode('-', $vencimento)));												
													?>

													<a href="pagar" class="dropdown-item d-flex border-bottom">
														
														<div class="wd-90p">
															<div class="d-flex">
																<h5 class="mb-0 name" style="color:red">R$ <?php echo $valorF ?></h5>
															</div>
															<p class="mb-0 desc"><?php echo $res[$i]['descricao'] ?> </p>
															<p class="time mb-0 text-start float-start ms-2"><?php echo $vencimentoF ?> </p>
														</div>
													</a>

												<?php } ?>
												
												
											</div>
											<div class="text-center dropdown-footer">
												<a class="btn btn-primary btn-sm btn-block text-center"  href="pagar">Ver Todas</a>
											</div>
										</div>
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

											<?php if($igreja_usuario == 0){?>

												<a class="dropdown-item" href="../painel-admin"><i class="fa fa-arrow-left"></i>Painel Administrador</a>
											<?php } ?>

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
							<img src="../img/igrejas/<?php echo $foto_painel ?>" class="main-logo  desktop-logo" alt="logo">
							<img src="../img/igrejas/<?php echo $foto_painel ?>" class="main-logo  desktop-dark" alt="logo">
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
											<li class="<?php echo @$membros ?>"><a class="slide-item" href="membros"> Membros</a></li>
											<li class="<?php echo @$visitantes ?>"><a class="slide-item" href="visitantes"> Visitantes</a></li>
											<li class="<?php echo @$aniversariantes ?>"><a class="slide-item" href="aniversariantes"> Aniversariantes</a></li>
											<li class="<?php echo @$pastores ?>"><a class="slide-item" href="pastores"> Pastores</a></li>
											<li class="<?php echo @$tesoureiros ?>"><a class="slide-item" href="tesoureiros"> Tesoureiros</a></li>
											<li class="<?php echo @$secretarios ?>"><a class="slide-item" href="secretarios"> Secretarios</a></li>
											<li class="<?php echo @$fornecedores ?>"><a class="slide-item" href="fornecedores"> Fornecedores</a></li>
											
											
										</ul>
									</li>



									<li class="slide <?php echo @$menu_cadastros ?>">
										<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
											<i class="fa fa-plus text-white mt-1"></i>
											<span class="side-menu__label" style="margin-left: 15px">Cadastros</span><i class="angle fe fe-chevron-right"></i></a>
											<ul class="slide-menu">
												

												<li class="<?php echo @$dados_igrejas ?>"><a class="slide-item" href="igrejas"> Dados da Igreja</a></li>

												<li class="<?php echo @$cultos ?>"><a class="slide-item" href="cultos"> Cultos</a></li>

												<li class="<?php echo @$alertas ?> "><a class="slide-item" href="alertas"> Alertas</a></li>

												<li class="<?php echo @$eventos ?> "><a class="slide-item" href="eventos"> Eventos</a></li>

												<li class="<?php echo @$versiculos ?> "><a class="slide-item" href="versiculos"> Versiculos</a></li>


												<?php if($mostrar_acessos == 'Sim'){ ?>

													<li class="<?php echo @$grupo_acessos ?> "><a class="slide-item" href="grupo_acessos"> Grupos</a></li>

													<li class="<?php echo @$acessos ?> "><a class="slide-item" href="acessos"> Acessos</a></li>

												<?php } ?>


												<li class="<?php echo @$informativos ?> "><a class="slide-item" href="informativos"> Informativos de Culto</a></li>
												
											</ul>
										</li>



										<li class="slide <?php echo @$menu_secretaria ?>">
											<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
												<i class="fa fa-cube text-white mt-1"></i>
												<span class="side-menu__label" style="margin-left: 15px">Secretária</span><i class="angle fe fe-chevron-right"></i></a>
												<ul class="slide-menu">

													<li class="<?php echo @$patrimonios ?> "><a class="slide-item" href="patrimonios"> Patrimônios</a></li>
													
													<li class="<?php echo @$documentos ?> "><a class="slide-item" href="documentos"> Documentos</a></li>

													<li class="<?php echo @$celulas ?> "><a class="slide-item" href="celulas"> Células</a></li>

													<li class="<?php echo @$grupos ?> "><a class="slide-item" href="grupos"> Grupos</a></li>

													<li class="<?php echo @$ministerios ?> "><a class="slide-item" href="ministerios"> Ministérios</a></li>

													<li class="<?php echo @$turmas ?> "><a class="slide-item" href="turmas"> EBD / Turmas</a></li>
													
												</ul>
											</li>



											<li class="slide <?php echo @$menu_financeiro ?>">
												<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
													<i class="fa fa-dollar text-white mt-1"></i>
													<span class="side-menu__label" style="margin-left: 15px">Financeiro</span><i class="angle fe fe-chevron-right"></i></a>
													<ul class="slide-menu">

														<li class="<?php echo @$receber ?> "><a class="slide-item" href="financeiro_geral"> Visão Geral</a></li>

														<li class="<?php echo @$receber ?> "><a class="slide-item" href="receber"> Receber</a></li>

														<li class="<?php echo @$pagar ?> "><a class="slide-item" href="pagar"> Despesas</a></li>

														<li class="<?php echo @$dizimos ?> "><a class="slide-item" href="dizimos"> Dizimos</a></li>

														<li class="<?php echo @$ofertas ?> "><a class="slide-item" href="ofertas"> Ofertas</a></li>

														<li class="<?php echo @$ofertas_missoes ?> "><a class="slide-item" href="missoes_recebidas"> Ofertas Missões Recebidas</a></li>


														<li class="<?php echo @$ofertas_missoes_enviadas ?> "><a class="slide-item" href="missoes_enviadas"> Ofertas Missões Enviadas</a></li>

														<li class="<?php echo @$doacoes ?> "><a class="slide-item" href="doacoes"> Doações</a></li>

														<li class="<?php echo @$vendas ?> "><a class="slide-item" href="vendas"> Vendas</a></li>

														<li class="<?php echo @$movimentacoes ?> "><a class="slide-item" href="movimentacoes"> Movimentações</a></li>

														<li class="<?php echo @$fechamentos ?> "><a class="slide-item" href="fechamentos"> Fechamentos</a></li>

													</ul>
												</li>

												<li class="slide <?php echo @$menu_relatorios ?>">
													<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
														<i class="fa fa-file-text text-white mt-1"></i>
														<span class="side-menu__label" style="margin-left: 15px">Relatórios</span><i class="angle fe fe-chevron-right"></i></a>
														<ul class="slide-menu">

															<li class="<?php echo @$rel_membros ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelMembros"> Membros</a></li>


															<li class="<?php echo @$rel_patrimonios ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelPatrimonios"> Patrimônios</a></li>


															<li><a class="slide-item <?php echo $ficha_visitante ?>" href="rel/ficha_visitante_class.php" target="_blank">Ficha Visitantes</a></li>


															<li class="<?php echo @$rel_financeiro ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelFin"> Relatório Financeiro</a></li>


															<li class="<?php echo @$rel_sintetico_despesas ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelSinDesp"> Rel Sintético Despesas</a></li>
															
															<li class="<?php echo @$rel_sintetico_receber ?> "><a class="slide-item" href="" data-bs-toggle="modal" data-bs-target="#modalRelSinRec"> Rel Sintético Receber</a></li>


															<li class="<?php echo @$rel_balanco ?> "><a class="slide-item" href="rel/balanco_anual_class.php" target="_blank"> Rel Balanço Anual</a></li>


														</ul>
													</li>

													<li class="slide <?php echo @$tarefas ?>">
														<a class="side-menu__item" href="tarefas">
															<i class="fa fa-calendar text-white"></i>
															<span class="side-menu__label" style="margin-left: 15px">Tarefas</span></a>
														</li>


														<li class="slide <?php echo @$anexos ?>">
															<a class="side-menu__item" href="anexos">
																<i class="fa fa-file text-white"></i>
																<span class="side-menu__label" style="margin-left: 15px">Anexos Sede</span></a>
															</li>


																<li class="slide <?php echo @$site ?>">
								<a class="side-menu__item" href="site">
									<i class="fa fa-globe text-white"></i>
									<span class="side-menu__label" style="margin-left: 15px">Site</span></a>
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

                                    <!--INTERNAL  INDEX JS (carregado acima; removida duplicidade) -->
                                    





								</body>

								</html>



								<div class="modal fade" id="modalRelMembros" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog modal-lg">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="exampleModalLabel">Relatório de Membros</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<form method="post" action="rel/rel_membros_class.php" target="_blank">
												<div class="modal-body">
													<div class="row">
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

															<input type="hidden" name="igreja" value="<?php echo $id_igreja ?>">
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



									<div class="modal fade" id="modalRelPatrimonios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel">Relatório de Patrimônios</h5>
													<button id="btn-fechar" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
												</div>
												<form method="post" action="rel/rel_patrimonio_class.php" target="_blank">
													<div class="modal-body">
														<div class="row">

															<input type="hidden" name="igreja" value="<?php echo $id_igreja ?>">


															<div class="col-md-4">
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

															<div class="col-md-4">
																<div class="mb-3">
																	<label >Status</label>
																	<select class="form-select form-select-sm" aria-label="Default select example" name="status">  
																		<option value="">Todos</option>
																		<option value="Sim">Ativo</option>
																		<option value="Não">Inativo</option>
																	</select>
																</div>
															</div>

															<div class="col-md-4">
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
																<label for="exampleFormControlInput1" class="form-label">CPF</label>
																<input type="text" class="form-control" id="cpf_usu" name="cpf_usu" placeholder="Insira o CPF" value="<?php echo $cpf_usu ?>">	
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



									<!-- Modal Rel Financeiro -->
									<div class="modal fade" id="modalRelFin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">	
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header bg-primary text-white">
													<h4 class="modal-title" id="exampleModalLabel">Relatório Financeiro</h4>
													<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
												</div>
												<form method="POST" action="rel/financeiro_class.php" target="_blank">
													<div class="modal-body">	
														<div class="row">
															<div class="col-md-4">
																<label>Data Inicial</label>
																<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
															</div>

															<div class="col-md-4">
																<label>Data Final</label>
																<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
															</div>

															<div class="col-md-4">
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






									<!-- Modal Rel Sintético Despesas -->
									<div class="modal fade" id="modalRelSinDesp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog modal-lg">
											<div class="modal-content">
												<div class="modal-header bg-primary text-white">
													<h4 class="modal-title" id="exampleModalLabel">Relatório Sintético Contas à Pagar</h4>
													<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
												</div>
												<form method="POST" action="rel/sintetico_class.php" target="_blank">
													<div class="modal-body">	
														<div class="row">
															<div class="col-md-4">
																<label>Data Inicial</label>
																<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
															</div>

															<div class="col-md-4">
																<label>Data Final</label>
																<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
															</div>

															<div class="col-md-4">
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
																<label>Tipo Filtro Contas</label>
																<select name="filtro_lancamento" class="form-select">					
																	<option value="fornecedor">Fornecedores</option>															

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
												<form method="POST" action="rel/sintetico_recebimentos_class.php" target="_blank">
													<div class="modal-body">	
														<div class="row">
															<div class="col-md-4">
																<label>Data Inicial</label>
																<input type="date" name="dataInicial" class="form-control" value="<?php echo $data_atual ?>">
															</div>

															<div class="col-md-4">
																<label>Data Final</label>
																<input type="date" name="dataFinal" class="form-control" value="<?php echo $data_atual ?>">
															</div>

															<div class="col-md-4">
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







									<!-- Modal Rel Locados -->
									<div class="modal fade" id="modalRelItens" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header bg-primary text-white">
													<h4 class="modal-title" id="exampleModalLabel">Itens Mais Locados</h4>
													<button id="btn-fechar-config" aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span class="text-white" aria-hidden="true">&times;</span></button>
												</div>
												<form method="POST" action="rel/itens_mais_locados_class.php" target="_blank">
													<div class="modal-body">	
														<div class="row">

															<div class="col-md-6">
																<label>Quantidade</label>
																<select name="quantidade" class="form-select">
																	<option value="">Todos</option>
																	<option value="10">10 Mais Locados</option>
																	<option value="20">20 Mais Locados</option>
																	<option value="30">30 Mais Locados</option>
																	<option value="50">50 Mais Locados</option>
																	<option value="100">100 Mais Locados</option>
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
											var target = document.getElementById('target-logo-l');
											var file = document.querySelector("#foto-logo-l").files[0];

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