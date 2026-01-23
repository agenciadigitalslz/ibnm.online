<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$data = @$_GET['data'];
$data1 = @$_GET['data1'];
$id_igreja = @$_GET['igreja']; 


$query = $pdo->prepare("SELECT * FROM tarefas where (data BETWEEN '$data' and '$data1') and igreja = '$id_igreja' ORDER BY data ASC, status asc, hora asc");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 

    $dataF = implode('/', array_reverse(explode('-', $res[$i]['data'])));
    $horaF = (new DateTime($res[$i]['hora']))->format('H:i');
    $descricaoF = mb_strimwidth($res[$i]['descricao'], 0, 32, "...");

    if($res[$i]['status'] == 'Agendada'){
        $icone = 'red';
        $card = 'red';
    }else{
        $icone = 'green';
        $card = 'green';
    }            
	
    $dados[] = array(
        'id' => $res[$i]['id'],
        'titulo' => $res[$i]['titulo'],   
        'data' => $res[$i]['data'],
        'hora' => $horaF,
        'descricao' => $res[$i]['descricao'],
        'descricaoF' => $descricaoF,
        'status' => $res[$i]['status'],
        'igreja' => $res[$i]['igreja'],
        'dataF' => $dataF,
        'icone' => $icone,
        'card' => $card,

    );
}



if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>@$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>