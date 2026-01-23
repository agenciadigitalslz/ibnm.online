<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$limite = (isset($_GET['limite'])) ? $_GET['limite'] : 5; 
$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
$id_igreja = @$_GET['igreja']; 

$inicio = ($limite * $pagina) - $limite; 

$query = $pdo->prepare("SELECT * FROM pastores where igreja = '$id_igreja' ORDER BY nome ASC LIMIT $inicio, $limite");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
	

	$data_nascF = implode('/', array_reverse(explode('-', $res[$i]['data_nasc'])));
	$data_cadF = implode('/', array_reverse(explode('-', $res[$i]['data_cad'])));
	

	

    $dados[] = array(
        'id' => $res[$i]['id'],
        'nome' => $res[$i]['nome'],
        'telefone' => $res[$i]['telefone'],
        'email' => $res[$i]['email'],
       
        'foto' => $res[$i]['foto'],
       
        'cpf' => $res[$i]['cpf'],
        'dataCad' => $data_cadF,
        'dataNasc' => $data_nascF,
        
        'endereco' => $res[$i]['endereco'],
       
    );
}



if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>@$dados, 'totalItems'=>@count($dados) + ($inicio)));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>