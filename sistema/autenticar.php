<?php 
@session_start();
require_once("conexao.php");

$id_usu = @$_POST['id'];
if($id_usu != ""){

	$query = $pdo->prepare("SELECT * from usuarios where id = :id");
	$query->bindValue(":id", "$id_usu");
	$query->execute();
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$linhas = @count($res);
	if($linhas > 0){

	$_SESSION['nome'] = $res[0]['nome'];
	$_SESSION['id'] = $res[0]['id'];
	$_SESSION['nivel'] = $res[0]['nivel'];
	$_SESSION['id_igreja'] = $res[0]['igreja'];


	//EXECUTAR NO LOG
	$tabela = 'usuarios';
	$acao = 'Login';
	$id_reg = 0;
	$descricao = 'Login';
	if($res[0]['nivel'] == 'Bispo'){
		$painel = 'Painel Administrativo';
		$igreja = 0;
	}else{
		$painel = 'Painel Igreja';
		$igreja = $res[0]['igreja'];
	}
	require_once("logs.php");

	

	echo '<script>window.location="painel"</script>';  
	}else{
		echo "<script>localStorage.setItem('id_usu', '')</script>";
		echo '<script>window.location="index.php"</script>';
	}
}

$usuario = @$_POST['usuario'];
$senha = @$_POST['senha'];
$salvar = @$_POST['salvar'];


$query = $pdo->prepare("SELECT * from usuarios where email = :email order by id asc limit 1");
$query->bindValue(":email", "$usuario");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);

if($linhas > 0){

	if(!password_verify($senha, $res[0]['senha_crip'])){
		echo '<script>window.alert("Dados Incorretos!!")</script>'; 
		echo '<script>window.location="index.php"</script>';  
		exit();
	}


	if($res[0]['ativo'] != 'Sim'){
		$_SESSION['msg'] = 'Seu Acesso foi desativado!'; 
		echo '<script>window.location="index.php"</script>';  
	}

	$_SESSION['nome'] = $res[0]['nome'];
	$_SESSION['id'] = $res[0]['id'];
	$_SESSION['nivel'] = $res[0]['nivel'];
	$_SESSION['id_igreja'] = $res[0]['igreja'];

	$id = $res[0]['id'];

	if($salvar == 'Sim'){
		echo "<script>localStorage.setItem('email_usu', '$usuario')</script>";
		echo "<script>localStorage.setItem('senha_usu', '$senha')</script>";
		echo "<script>localStorage.setItem('id_usu', '$id')</script>";
	}else{
		echo "<script>localStorage.setItem('email_usu', '')</script>";
		echo "<script>localStorage.setItem('senha_usu', '')</script>";
		echo "<script>localStorage.setItem('id_usu', '')</script>";
	}
	

	//EXECUTAR NO LOG
	$tabela = 'usuarios';
	$acao = 'Login';
	$id_reg = 0;
	$descricao = 'Login';
	if($res[0]['nivel'] == 'Bispo'){
		$painel = 'Painel Administrativo';
		$igreja = 0;
	}else{
		$painel = 'Painel Igreja';
		$igreja = $res[0]['igreja'];
	}
	require_once("logs.php");
	
	

	if($res[0]['igreja'] == 0){
		echo "<script>window.location='painel-admin'</script>";
	}else{
		echo "<script>window.location='painel-igreja'</script>";
	}		
				
	
	

}else{
	$_SESSION['msg'] = 'Dados Incorretos!'; 
	echo '<script>window.location="index.php"</script>';  
}


 ?>

