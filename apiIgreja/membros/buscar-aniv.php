<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$dataMes = Date('m');
$dataDia = Date('d');

$id_igreja = @$_GET['igreja']; 

$busca = @$_GET['busca']; 

if(@$busca == "Dia"){
	$classe_dia = 'text-primary';
	$classe_mes = 'text-dark';

	$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia' order by data_nasc asc, id desc ");

	$query_pastores = $pdo->query("SELECT * FROM pastores where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia' order by data_nasc asc, id desc");
	
}else{
	$classe_mes = 'text-primary';
	$classe_dia = 'text-dark';

	$query = $pdo->query("SELECT * FROM membros where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' order by data_nasc asc, id desc ");

	$query_pastores = $pdo->query("SELECT * FROM pastores where igreja = '$id_igreja' and month(data_nasc) = '$dataMes' order by data_nasc asc, id desc ");
	
}

	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = count($res);

	$res_pastores = $query_pastores->fetchAll(PDO::FETCH_ASSOC);
	$total_reg_pastores = count($res_pastores);
	if($total_reg > 0 || $total_reg_pastores > 0){


	for($i=0; $i < $total_reg; $i++){
					foreach ($res[$i] as $key => $value){} 

						$nome = $res[$i]['nome'];
					$cpf = $res[$i]['cpf'];
					$email = $res[$i]['email'];
					$telefone = $res[$i]['telefone'];
					$endereco = $res[$i]['endereco'];
					$foto = $res[$i]['foto'];
					$data_nasc = $res[$i]['data_nasc'];
					$data_cad = $res[$i]['data_cad'];
					$obs = $res[$i]['obs'];
					$igreja = $res[$i]['igreja'];
					$cargo = $res[$i]['cargo'];
					$data_bat = $res[$i]['data_batismo'];
					$igreja = $res[$i]['igreja'];
					$ativo = $res[$i]['ativo'];
					$id = $res[$i]['id'];
					$estado = $res[$i]['estado_civil'];

					if($obs != ""){
						$classe_obs = 'text-warning';
					}else{
						$classe_obs = 'text-secondary';
					}


					$query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_ig = $res_con[0]['nome'];
					}else{
						$nome_ig = $nome_igreja_sistema;
					}

					$query_con = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_cargo = $res_con[0]['nome'];
					}else{
						$nome_cargo = '';
					}					


					$dia_mes = Date('d');
					$partes = explode('-', $data_nasc);
					$dia = $partes[2];

					if($dia == $dia_mes){
						$classe_aniv = 'blue';
						$classe_whats = '';
					}else{
						$classe_aniv = '#000';
						$classe_whats = 'd-none';
					}


	$data_nascF = implode('/', array_reverse(explode('-', $res[$i]['data_nasc'])));
	$data_cadF = implode('/', array_reverse(explode('-', $res[$i]['data_cad'])));
	$data_batF = implode('/', array_reverse(explode('-', $res[$i]['data_batismo'])));

	if($data_batF == '00/00/0000'){
		$data_batF = 'Não Batizado!';
	}

    $dados[] = array(
        'id' => $res[$i]['id'],
        'nome' => $res[$i]['nome'],
        'telefone' => $res[$i]['telefone'],
        'email' => $res[$i]['email'],
        'ativo' => $res[$i]['ativo'],
        'foto' => $res[$i]['foto'],
        'cargo' => $nome_cargo,
        'cpf' => $res[$i]['cpf'],
        'dataCad' => $data_cadF,
        'dataNasc' => $data_nascF,
        'dataBat' => $data_batF,
        'endereco' => $res[$i]['endereco'],
        'estCivil' => $res[$i]['estado_civil'],
        'classeAniv' => $classe_aniv,
    );
}




					for($i=0; $i < $total_reg_pastores; $i++){
					foreach ($res_pastores[$i] as $key => $value){} 

						$nome = $res_pastores[$i]['nome'];
					$cpf = $res_pastores[$i]['cpf'];
					$email = $res_pastores[$i]['email'];
					$telefone = $res_pastores[$i]['telefone'];
					$endereco = $res_pastores[$i]['endereco'];
					$foto = $res_pastores[$i]['foto'];
					$data_nasc = $res_pastores[$i]['data_nasc'];
					$data_cad = $res_pastores[$i]['data_cad'];
					$obs = $res_pastores[$i]['obs'];
					$igreja = $res_pastores[$i]['igreja'];
					$id = $res_pastores[$i]['id'];

					if($obs != ""){
						$classe_obs = 'text-warning';
					}else{
						$classe_obs = 'text-secondary';
					}


					$query_con = $pdo->query("SELECT * FROM igrejas where id = '$igreja'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_ig = $res_con[0]['nome'];
					}else{
						$nome_ig = $nome_igreja_sistema;
					}

					
					//retirar quebra de texto do obs
					$obs = str_replace(array("\n", "\r"), ' + ', $obs);

					$data_nascF = implode('/', array_reverse(explode('-', $data_nasc)));
					$data_cadF = implode('/', array_reverse(explode('-', $data_cad)));
					

					$dia_mes = Date('d');
					$partes = explode('-', $data_nasc);
					$dia = $partes[2];

					if($dia == $dia_mes){
						$classe_aniv = 'blue';
						$classe_whats = '';
					}else{
						$classe_aniv = '#000';
						$classe_whats = 'd-none';
					}



		$dados[] = array(
        'id' => $res_pastores[$i]['id'],
        'nome' => $res_pastores[$i]['nome'],
        'telefone' => $res_pastores[$i]['telefone'],
        'email' => $res_pastores[$i]['email'],
        'ativo' => 'Sim',
        'foto' => $res_pastores[$i]['foto'],
        'cargo' => 'Pastor',
        'cpf' => $res_pastores[$i]['cpf'],
        'dataCad' => $data_cadF,
        'dataNasc' => $data_nascF,
        'dataBat' => '',
        'endereco' => $res_pastores[$i]['endereco'],
        'estCivil' => '',
        'classeAniv' => $classe_aniv,
    );


				}




}

if(count($res) > 0 || count($res_pastores) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>@$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>