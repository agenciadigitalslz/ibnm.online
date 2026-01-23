<?php 

include_once('../conexao.php');

$id_igreja = @$_GET['igreja']; 
$query = $pdo->query("SELECT * FROM igrejas order by matriz desc, nome asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);

	for($i=0; $i < $total_reg; $i++){
		foreach ($res[$i] as $key => $value){} 

		$nome_reg = $res[$i]['nome'];
		$id_reg = $res[$i]['id'];

		if($id_reg != $id_igreja){

			$dados[] = array(
				'id' => $res[$i]['id'],
				'nome' => $res[$i]['nome'],
			);

		}
	}

	if(count($res) > 0){
		$result = json_encode(array('success'=>true, 'resultado'=>$dados));
	}else{
		$result = json_encode(array('success'=>false, 'resultado'=>'0'));
	}

	echo $result;

	?>