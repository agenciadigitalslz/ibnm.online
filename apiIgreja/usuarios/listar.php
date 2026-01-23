<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$limite = (isset($_GET['limite'])) ? $_GET['limite'] : 5; 
$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
$id_igreja = @$_GET['igreja']; 

$inicio = ($limite * $pagina) - $limite; 

$query = $pdo->prepare("SELECT * FROM usuarios where igreja = '$id_igreja' ORDER BY nome ASC LIMIT $inicio, $limite");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
	

    $dados[] = array(
        'id' => $res[$i]['id'],
        'nome' => $res[$i]['nome'],
        'senha' => $res[$i]['senha'],
        'email' => $res[$i]['email'],        
        'foto' => $res[$i]['foto'],
        'cargo' => $res[$i]['nivel'],
        'cpf' => $res[$i]['cpf'],        
    );
}



if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>@$dados, 'totalItems'=>@count($dados) + ($inicio)));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>