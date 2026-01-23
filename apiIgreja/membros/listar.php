<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$limite = (isset($_GET['limite'])) ? $_GET['limite'] : 5; 
$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
$id_igreja = @$_GET['igreja']; 

$inicio = ($limite * $pagina) - $limite; 

$query = $pdo->prepare("SELECT * FROM membros where igreja = '$id_igreja' ORDER BY nome ASC LIMIT $inicio, $limite");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
	$cargo = $res[$i]['cargo'];
	$query_con = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_cargo = $res_con[0]['nome'];
					}else{
						$nome_cargo = '';
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
    );
}



if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>@$dados, 'totalItems'=>@count($dados) + ($inicio)));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>