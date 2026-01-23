<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$data = @$_GET['data'];
$data1 = @$_GET['data1'];
$id_igreja = @$_GET['igreja']; 
$data_atual = date('Y-m-d');

$query = $pdo->prepare("SELECT * FROM dizimos where (data BETWEEN '$data' and '$data1') and igreja = '$id_igreja' ORDER BY data ASC, id asc");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);


for($i=0; $i < count($res); $i++){
                    foreach ($res[$i] as $key => $value){} 

                    $membro = $res[$i]['membro'];                    
                    $valor = $res[$i]['valor'];
                    $data = $res[$i]['data']; 
                    $usuario = $res[$i]['usuario'];
                    $igreja = $res[$i]['igreja'];
                    
                    $id = $res[$i]['id'];


                   

                    $query_con = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $usuario_cad = $res_con[0]['nome'];

                    }else{
                        $usuario_cad = '';
                    }

                    $query_con = $pdo->query("SELECT * FROM membros where id = '$membro'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_membro = $res_con[0]['nome'];

                    }else{
                        $nome_membro = 'Membro não Informado';
                    }


                  
                    $valorF = number_format($valor, 2, ',', '.');
                    $dataF = implode('/', array_reverse(explode('-', $data)));

                    $query2 = $pdo->query("SELECT * FROM config");
                    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                    $limitar_tesoureiro = $res2[0]['limitar_tesoureiro'];
                       
	
    $dados[] = array(
        'id' => $id,
        'descricao' => $nome_membro,
        'valor' => $valorF,        
        'usuario' => $usuario_cad,
        'data' => $dataF,        
        'limitar' => $limitar_tesoureiro,
    );
}



if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>@$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>