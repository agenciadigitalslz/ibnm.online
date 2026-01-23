<?php 

include_once('../conexao.php');

$postjson = json_decode(file_get_contents('php://input'), true);

$data = @$_GET['data'];
$data1 = @$_GET['data1'];
$id_igreja = @$_GET['igreja']; 
$data_atual = date('Y-m-d');

$tipo_mov = '%'.@$_GET['tipo'].'%'; 

$query = $pdo->prepare("SELECT * FROM movimentacoes where (data BETWEEN '$data' and '$data1') and igreja = '$id_igreja' and tipo LIKE '$tipo_mov' ORDER BY data ASC, id asc");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$total_valor = 0;
$total_entradas = 0;
$total_saidas = 0;
$classe = "";
$classe_total = "";
for($i=0; $i < count($res); $i++){
                    foreach ($res[$i] as $key => $value){} 

                    $descricao = $res[$i]['descricao'];                    
                    $valor = $res[$i]['valor'];
                    $data = $res[$i]['data']; 
                    $usuario = $res[$i]['usuario'];
                    $igreja = $res[$i]['igreja'];
                    $tipo = $res[$i]['tipo'];
                    $movimento = $res[$i]['movimento'];
                    
                    $id = $res[$i]['id'];

                    if($tipo == 'Entrada'){
                        $total_entradas += $valor;
                        $classe = 'green';
                    }else{
                        $total_saidas += $valor;
                        $classe = 'red';
                    }

                    $total_valor = $total_entradas - $total_saidas;

                    if($total_valor < 0 || $tipo_mov == 'Saída' ){
                        $classe_total = 'red';
                    }else{
                        $classe_total = 'green';
                    }

                    $query_con = $pdo->query("SELECT * FROM usuarios where id = '$usuario'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $usuario_cad = $res_con[0]['nome'];

                    }else{
                        $usuario_cad = '';
                    }


                  
                    $valorF = number_format($valor, 2, ',', '.');
                    $dataF = implode('/', array_reverse(explode('-', $data)));


                   
                       
	
    $dados[] = array(
        'id' => $id,
        'descricao' => $descricao,
        'valor' => $valorF,        
        'usuario' => $usuario_cad,
        'data' => $dataF,    
        'tipo' => $tipo,    
        'movimento' => $movimento,
        'classe' => $classe,
        
    );
}

 $total_valorF = number_format($total_valor, 2, ',', '.');

if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'resultado'=>@$dados, 'total_valor'=>@$total_valorF, 'classe_total'=>@$classe_total));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>