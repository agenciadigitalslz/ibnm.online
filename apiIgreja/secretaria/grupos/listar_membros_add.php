<?php 

require_once("../../conexao.php");
$igreja = @$_GET['igreja']; 
$celula = @$_GET['celula'];


$query = $pdo->query("SELECT * FROM membros where igreja = '$igreja' and ativo = 'Sim' order by nome asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);
if($total_reg > 0){

	for($i=0; $i < $total_reg; $i++){
		foreach ($res[$i] as $key => $value){} 

			$nome_reg = $res[$i]['nome'];
		$id_reg = $res[$i]['id'];

		$query2 = $pdo->query("SELECT * FROM grupos_membros where igreja = '$igreja' and membro = '$id_reg' and grupo = '$celula'");
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res2) == 0){

    $dados[] = array(
        'id' => $res[$i]['id'],
        'nome' => $res[$i]['nome'],
    );
}

}

}

if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>