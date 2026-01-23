<?php 

require_once("../../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);

$buscar = '%' .@$_GET['buscar']. '%';
$id_igreja = @$_GET['igreja']; 

$query = $pdo->prepare("SELECT * from celulas where nome LIKE '$buscar' and igreja = '$id_igreja' order by id desc");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for($i=0; $i < count($res); $i++){
                    foreach ($res[$i] as $key => $value){} 

                        $nome = $res[$i]['nome'];   
                    $dias = $res[$i]['dias'];
                    $hora = $res[$i]['hora'];
                    $local = $res[$i]['local'];
                    $pastor = $res[$i]['pastor'];
                    $coordenador = $res[$i]['coordenador'];
                    $lider1 = $res[$i]['lider1'];
                    $lider2 = $res[$i]['lider2'];
                    $obs = $res[$i]['obs'];
                    $igreja = $res[$i]['igreja'];
                    $id = $res[$i]['id'];


                    //totalizar membros
                    $query2 = $pdo->query("SELECT * FROM celulas_membros where igreja = '$id_igreja' and celula = '$id'");
                    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                    $membros_celula = count($res2);


                    if($obs != ""){
                        $classe_obs = 'text-warning';
                    }else{
                        $classe_obs = 'text-secondary';
                    }


                    $query_con = $pdo->query("SELECT * FROM pastores where id = '$pastor'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_pastor = $res_con[0]['nome'];
                    }else{
                        $nome_pastor = 'Nenhum';
                    }

                    $query_con = $pdo->query("SELECT * FROM membros where id = '$coordenador'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_coordenador = $res_con[0]['nome'];
                    }else{
                        $nome_coordenador = 'Nenhum';
                    }

                    $query_con = $pdo->query("SELECT * FROM membros where id = '$lider1'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_lider1 = $res_con[0]['nome'];
                    }else{
                        $nome_lider1 = 'Nenhum';
                    }


                    $query_con = $pdo->query("SELECT * FROM membros where id = '$lider2'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_lider2 = $res_con[0]['nome'];
                    }else{
                        $nome_lider2 = 'Nenhum';
                    }

    $dados[] = array(
        'id' => $res[$i]['id'],
        'nome' => $nome,
        'dias' => $dias,
        'hora' => $hora,
        'local' => $local,
        'pastor' => $nome_pastor,
        'coordenador' => $nome_coordenador,
        'lider1' => $nome_lider1,
        'lider2' => $nome_lider2,
        'obs' => $obs,
        'membros_celula' => $membros_celula,
       
       
    );
}

if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'clientes'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>