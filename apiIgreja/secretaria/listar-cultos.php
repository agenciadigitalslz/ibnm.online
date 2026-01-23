<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$limite = (isset($_GET['limite'])) ? $_GET['limite'] : 5; 
$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
$id_igreja = @$_GET['igreja']; 

$inicio = ($limite * $pagina) - $limite; 

$query = $pdo->prepare("SELECT * FROM cultos where igreja = '$id_igreja' ORDER BY id DESC LIMIT $inicio, $limite");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
	
                    $nome = $res[$i]['nome'];   
                    $dia = $res[$i]['dia'];
                    $hora = $res[$i]['hora'];
                    $descricao = $res[$i]['descricao'];
                    $obs = $res[$i]['obs'];
                    $igreja = $res[$i]['igreja'];
                    $id = $res[$i]['id'];


    $dados[] = array(
        'id' => $res[$i]['id'],
        'nome' => $res[$i]['nome'],        
        'dia' => $dia,
        'hora' => $hora,
        'descricao' => $descricao,
        'obs' => $obs,
       
    );
}



if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>@$dados, 'totalItems'=>@count($dados) + ($inicio)));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>