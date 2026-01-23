<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$data = @$_GET['data'];
$data1 = @$_GET['data1'];
$id_igreja = @$_GET['igreja']; 
$data_atual = date('Y-m-d');
$pago = '%'.@$_GET['pago'].'%';

$query = $pdo->prepare("SELECT * FROM receber where (vencimento BETWEEN '$data' and '$data1') and igreja = '$id_igreja' and pago LIKE '$pago' ORDER BY vencimento ASC, pago asc, id asc");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);


for($i=0; $i < count($res); $i++){
                    foreach ($res[$i] as $key => $value){} 

                    $descricao = $res[$i]['descricao'];
                    $fornecedor = $res[$i]['membro'];
                    $valor = $res[$i]['valor'];
                    $vencimento = $res[$i]['vencimento'];
                    
                    $pago = $res[$i]['pago'];

                    $usuario_cad = $res[$i]['usuario_cad'];
                    $usuario_baixa = $res[$i]['usuario_baixa'];
                    $data_baixa = $res[$i]['data_baixa'];
                    $data = $res[$i]['data'];
                    $igreja = $res[$i]['igreja'];
                    
                    $id = $res[$i]['id'];


                   

                    if($pago == 'Sim'){
                        $classe = 'green';
                        $ocultar = 'd-none';

                    }else{
                        $classe = 'red';
                        $ocultar = '';
                    }

                    if($vencimento >= $data_atual){
                        $classe_linha = '';

                    }else{
                        if($pago != 'Sim'){
                            $classe_linha = 'text-danger';
                        }else{
                            $classe_linha = '';
                        }                       

                    }


                    $query_con = $pdo->query("SELECT * FROM membros where id = '$fornecedor'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_for = $res_con[0]['nome'];
                    }else{
                        $nome_for = 'Sem Fornecedor';
                    }

                    $query_con = $pdo->query("SELECT * FROM usuarios where id = '$usuario_cad'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $usuario_cad = $res_con[0]['nome'];

                    }else{
                        $usuario_cad = '';
                    }


                    $query_con = $pdo->query("SELECT * FROM usuarios where id = '$usuario_baixa'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $usuario_baixa = $res_con[0]['nome'];
                    }else{
                        $usuario_baixa = '';
                    }


                   

                    $valorF = number_format($valor, 2, ',', '.');
                    $dataF = implode('/', array_reverse(explode('-', $data)));
                    $data_baixaF = implode('/', array_reverse(explode('-', $data_baixa)));
                    $vencimentoF = implode('/', array_reverse(explode('-', $vencimento)));    
	
    $dados[] = array(
        'id' => $res[$i]['id'],
        'descricao' => $descricao,   
        'fornecedor' => $nome_for,
        'valor' => $valorF,
        'vencimento' => $vencimentoF,
        
        
        'pago' => $pago,
        'usuario_cad' => $usuario_cad,
        'usuario_baixa' => $usuario_baixa,
        'data' => $dataF,
        'data_baixa' => $data_baixaF,
        
        'classe' => $classe,

    );
}



if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>@$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>