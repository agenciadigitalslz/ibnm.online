<?php 

include_once('conexao.php');

$query = $pdo->query("SELECT * FROM igrejas order by matriz desc, nome asc");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$total_reg = count($res);

		for($i=0; $i < $total_reg; $i++){
			foreach ($res[$i] as $key => $value){} 

				$nome = $res[$i]['nome'];
					//$pastor = $res[$i]['pastor'];
			$imagem = $res[$i]['imagem'];
			$matriz = $res[$i]['matriz'];
			$pastor = $res[$i]['pastor'];
			$id_ig = $res[$i]['id'];

			if($matriz == 'Sim'){
				$bordacard = 'bordacardsede';
				$classe = 'text-danger';
			}else{
				$bordacard = 'bordacard';
				$classe = 'text-primary';
			}

			$query_m = $pdo->query("SELECT * FROM membros where igreja = '$id_ig' and ativo = 'Sim'");
			$res_m = $query_m->fetchAll(PDO::FETCH_ASSOC);
			$membrosCad = @count($res_m);


			$query_con = $pdo->query("SELECT * FROM pastores where id = '$pastor'");
			$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
			if(count($res_con) > 0){
				$nome_p = $res_con[0]['nome'];
			}else{
				$nome_p = 'Não Definido';
			}



    $dados[] = array(
        'id' => $id_ig,
        'nome_igreja' => $nome,
        'quantidade_membros' => $membrosCad,
        'nome_pastor' => $nome_p,
        'foto' => $imagem,
        
    );
}



if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>