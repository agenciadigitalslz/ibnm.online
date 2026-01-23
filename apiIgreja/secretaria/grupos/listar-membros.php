<?php 

require_once("../../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);

$limite = (isset($_GET['limite'])) ? $_GET['limite'] : 5; 
$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
$celula = @$_GET['celula']; 

$inicio = ($limite * $pagina) - $limite; 

$query_celula = $pdo->query("SELECT * FROM grupos where id = '$celula'");
$res_celula = $query_celula->fetchAll(PDO::FETCH_ASSOC);
$nome_celula = $res_celula[0]['nome'];

$query = $pdo->prepare("SELECT * FROM grupos_membros where grupo = '$celula' ORDER BY id desc LIMIT $inicio, $limite");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
    $membro = $res[$i]['membro'];

    $query2 = $pdo->prepare("SELECT * FROM membros where id = '$membro'");    
    $query2->execute();
    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);


	$cargo = $res2[0]['cargo'];
	$query_con = $pdo->query("SELECT * FROM cargos where id = '$cargo'");
					$res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
					if(count($res_con) > 0){
						$nome_cargo = $res_con[0]['nome'];
					}else{
						$nome_cargo = '';
					}

	$data_nascF = implode('/', array_reverse(explode('-', $res2[0]['data_nasc'])));
	$data_cadF = implode('/', array_reverse(explode('-', $res2[0]['data_cad'])));
	$data_batF = implode('/', array_reverse(explode('-', $res2[0]['data_batismo'])));

	if($data_batF == '00/00/0000'){
		$data_batF = 'Não Batizado!';
	}

    $dados[] = array(
        'id' => $res[$i]['id'],
        'nome' => $res2[0]['nome'],
        'telefone' => $res2[0]['telefone'],
        'email' => $res2[0]['email'],
        'ativo' => $res2[0]['ativo'],
        'foto' => $res2[0]['foto'],
        'cargo' => $nome_cargo,
        'cpf' => $res2[0]['cpf'],
        'dataCad' => $data_cadF,
        'dataNasc' => $data_nascF,
        'dataBat' => $data_batF,
        'endereco' => $res2[0]['endereco'],
        'estCivil' => $res2[0]['estado_civil'],
        'id_celula' => $celula,
        'nome_celula' => $nome_celula,
    );
}



if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>@$dados, 'totalItems'=>@count($dados) + ($inicio)));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>