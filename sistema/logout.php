<?php 
@session_start();

//EXECUTAR NO LOG
	$tabela = 'usuarios';
	$acao = 'Logout';
	$id_reg = 0;
	$descricao = 'Logout';
	if($_SESSION['nivel_usuario'] == 'bispo'){
		$painel = 'Painel Administrativo';
		$igreja = 0;
	}else{
		$painel = 'Painel Igreja';
		$igreja = $_SESSION['id_igreja'];
	}
	require_once("logs.php");

@session_destroy();
echo "<script>window.location='index.php'</script>";
 ?>