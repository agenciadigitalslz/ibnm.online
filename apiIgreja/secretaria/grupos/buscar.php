<?php 

require_once("../../conexao.php");

$postjson = json_decode(file_get_contents('php://input'), true);

$buscar = '%' .@$_GET['buscar']. '%';
$id_igreja = @$_GET['igreja']; 

$query = $pdo->prepare("SELECT * from grupos where nome LIKE '$buscar' and igreja = '$id_igreja' order by id desc");

$query->execute();

$res = $query->fetchAll(PDO::FETCH_ASSOC);

for($i=0; $i < count($res); $i++){
                    foreach ($res[$i] as $key => $value){} 

                       $nome = $res[$i]['nome'];    
                    $dias = $res[$i]['dias'];
                    $hora = $res[$i]['hora'];
                    $local = $res[$i]['local'];
                    $pastor = $res[$i]['pastor'];
                    $tesoureiro = $res[$i]['tesoureiro'];
                    $secretario = $res[$i]['secretario'];
                    $regente = $res[$i]['regente'];
                    $lider1 = $res[$i]['lider1'];
                    $lider2 = $res[$i]['lider2'];
                    $obs = $res[$i]['obs'];
                    $igreja = $res[$i]['igreja'];
                    $id = $res[$i]['id'];


                    //totalizar membros
                    $query2 = $pdo->query("SELECT * FROM grupos_membros where igreja = '$id_igreja' and grupo = '$id'");
                    $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
                    $membros_grupo = count($res2);


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

                    $query_con = $pdo->query("SELECT * FROM membros where id = '$regente'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_regente = $res_con[0]['nome'];
                    }else{
                        $nome_regente = 'Nenhum';
                    }


                        $query_con = $pdo->query("SELECT * FROM tesoureiros where id = '$tesoureiro'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_tes = $res_con[0]['nome'];
                    }else{
                        $nome_tes = 'Nenhum';
                    }

                        $query_con = $pdo->query("SELECT * FROM secretarios where id = '$secretario'");
                    $res_con = $query_con->fetchAll(PDO::FETCH_ASSOC);
                    if(count($res_con) > 0){
                        $nome_sec = $res_con[0]['nome'];
                    }else{
                        $nome_sec = 'Nenhum';
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
        'tesoureiro' => $nome_tes,
        'secretario' => $nome_sec,
        'regente' => $nome_regente,
        'lider1' => $nome_lider1,
        'lider2' => $nome_lider2,
        'obs' => $obs,
        'membros_grupo' => $membros_grupo,
       
       
    );
}
if(count($res) > 0){
    $result = json_encode(array('success'=>true, 'clientes'=>$dados));
}else{
    $result = json_encode(array('success'=>false, 'resultado'=>'0'));
}

echo $result;

?>