<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$id = @$_GET['id'];

$query = $pdo->prepare("SELECT * from membros where id = '$id' order by nome ASC");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i=0; $i < count($res); $i++) { 
    foreach ($res[$i] as $key => $value) {
    }

    
    $dataNasc = implode('/', array_reverse(explode('-', $res[$i]['data_nasc'])));
    $dataBat = implode('/', array_reverse(explode('-', $res[$i]['data_batismo'])));

   

    $dados = array(
    'id' => $res[$i]['id'],
    'nome' => $res[$i]['nome'],
    'telefone' => $res[$i]['telefone'],
    'email' => $res[$i]['email'],
    'endereco' => $res[$i]['endereco'],
    'ativo' => $res[$i]['ativo'],
    'cpf' => $res[$i]['cpf'],
    'cargo' => $res[$i]['cargo'],
    'estado' => $res[$i]['estado_civil'],
    'dataNasc' => $res[$i]['data_nasc'],
    'dataBat' => $res[$i]['data_batismo'],
    'tumb' => $res[$i]['foto'],
    );
}

if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'dados'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>